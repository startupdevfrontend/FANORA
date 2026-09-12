<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Profile;
use App\Services\AuditService;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function __construct(
        protected MediaService $media,
        protected AuditService $audit,
    ) {
    }

    public function index(): View
    {
        return view('profile.index', ['user' => auth()->user()]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $profile = $user->profile ?? Profile::create(['user_id' => $user->id]);

        $user->update([
            'name' => $request->string('name'),
            'username' => $request->string('username'),
        ]);

        $data = [
            'bio' => $request->input('bio'),
            'location' => $request->input('location'),
            'website' => $request->input('website'),
        ];

        if ($request->hasFile('avatar')) {
            if ($profile->avatar_path) {
                $this->media->delete($profile->avatar_path);
            }

            $data['avatar_path'] = $this->media->storeProfileImage($request->file('avatar'), "avatars/{$user->id}");
        }

        if ($request->hasFile('cover')) {
            if ($profile->cover_path) {
                $this->media->delete($profile->cover_path);
            }

            $data['cover_path'] = $this->media->storeProfileImage($request->file('cover'), "covers/{$user->id}");
        }

        $profile->update($data);

        $this->audit->log($user, 'profile.updated', $profile, null, $request->only('name', 'username', 'bio'));

        return back()->with('status', 'Perfil atualizado com sucesso.');
    }

    public function settings(): View
    {
        return view('settings.index', ['user' => auth()->user()]);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // SECURITY: 'hashed' cast handles hashing; pass plain value to avoid double-hash
        auth()->user()->fill(['password' => (string) $request->string('password')])->save();

        return back()->with('status', 'Senha atualizada com sucesso.');
    }

    public function export(): View
    {
        $user = auth()->user()->load([
            'profile',
            'consents',
            'posts' => fn ($q) => $q->with('media'),
        ]);

        return view('settings.export-data', compact('user'));
    }

    public function requestDeletion(Request $request): RedirectResponse
    {
        $request->validate(['confirmation' => ['required', 'in:DELETAR']]);

        $user = auth()->user();

        // LGPD: full account deletion request. Profile and relation cleanup.
        // SECURITY: use forceFill for privileged fields (is_active, email, username)
        $user->forceFill([
            'name' => 'Usuário excluído',
            'username' => 'user_'.$user->id,
            'email' => 'deleted_'.$user->id.'@removido.fanora.app',
            'is_active' => false,
            'password' => \Illuminate\Support\Str::random(60), // hashed via cast
        ])->save();

        $this->audit->log($user, 'account.deletion_requested', $user);

        auth()->loginUsingId($user->id);
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Sua conta foi desativada e os dados serão removidos conforme a Política de Privacidade.');
    }
}