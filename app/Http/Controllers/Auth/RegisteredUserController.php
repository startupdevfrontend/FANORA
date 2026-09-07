<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ConsentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Profile;
use App\Models\User;
use App\Services\ConsentService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(protected ConsentService $consents)
    {
    }

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->string('name'),
            'username' => $request->string('username'),
            'email' => $request->string('email'),
            'password' => $request->string('password'),
            'birth_date' => $request->date('birth_date'),
            'age_confirmed' => true,
            'role' => 'user',
            'is_active' => true,
        ]);

        Profile::create(['user_id' => $user->id]);

        $this->consents->recordMany($user, [
            ConsentType::Terms,
            ConsentType::Privacy,
            ConsentType::Age,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('home');
    }
}