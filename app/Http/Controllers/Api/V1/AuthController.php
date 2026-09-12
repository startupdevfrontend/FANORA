<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ConsentType;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use App\Services\ConsentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function __construct(protected ConsentService $consents)
    {
    }

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'username' => ['required', 'string', 'lowercase', 'alpha_dash', 'min:3', 'max:30', Rule::unique('users')],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
            'birth_date' => ['required', 'date', 'before_or_equal:'.now()->subYears(18)->toDateString()],
            'terms' => ['accepted'],
            'privacy' => ['accepted'],
        ]);

        // SECURITY: privileged fields set explicitly
        $user = new User();
        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->password = $validated['password'];
        $user->birth_date = $validated['birth_date'];
        $user->age_confirmed = true;
        $user->role = 'user';
        $user->is_active = true;
        $user->save();

        Profile::create(['user_id' => $user->id]);

        $this->consents->recordMany($user, [ConsentType::Terms, ConsentType::Privacy, ConsentType::Age]);

        $token = $user->createToken('api')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['nullable', 'string', 'email'],
            'username' => ['nullable', 'string'],
            'password' => ['required', 'string'],
        ]);

        $key = isset($validated['email']) ? 'email' : 'username';

        if (! Auth::attempt([$key => $validated[$key] ?? null, 'password' => $validated['password']])) {
            return response()->json(['message' => 'Credenciais inválidas.'], 401);
        }

        $user = Auth::user();

        if (! $user->isActive()) {
            return response()->json(['message' => 'Conta desativada.'], 403);
        }

        $token = $user->createToken('api')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado.']);
    }
}