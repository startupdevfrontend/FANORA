<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(protected AuditService $audit)
    {
    }

    public function index(Request $request): View
    {
        $users = User::query()
            ->with('creatorProfile')
            ->when($request->query('search'), fn ($q, $s) => $q->where(function ($w) use ($s) {
                $w->where('name', 'like', "%{$s}%")
                    ->orWhere('username', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%");
            }))
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->load(['profile', 'creatorProfile.categories', 'consents']);

        return view('admin.users.show', compact('user'));
    }

    public function toggleActive(User $user): RedirectResponse
    {
        $this->authorize('activate', [User::class, $user]);

        $user->update(['is_active' => ! $user->is_active]);

        $this->audit->log(auth()->user(), 'user.toggled_active', $user, ['is_active' => ! $user->is_active], ['is_active' => $user->is_active]);

        return back()->with('status', 'Usuário atualizado.');
    }

    public function promote(User $user): RedirectResponse
    {
        $this->authorize('manage', [User::class, $user]);

        $user->update(['role' => 'admin']);

        $this->audit->log(auth()->user(), 'user.promoted_admin', $user);

        return back()->with('status', 'Usuário promovido a administrador.');
    }

    public function demote(User $user): RedirectResponse
    {
        $this->authorize('manage', [User::class, $user]);

        abort_if($user->id === auth()->id(), 422, 'Você não pode remover o próprio acesso.');

        $user->update(['role' => 'user']);

        $this->audit->log(auth()->user(), 'user.demoted', $user);

        return back()->with('status', 'Usuário rebaixado.');
    }

    public function suspend(User $user): RedirectResponse
    {
        $this->authorize('manage', [User::class, $user]);

        abort_if($user->id === auth()->id(), 422, 'Você não pode suspender a própria conta.');

        $user->update(['is_active' => false]);

        $this->audit->log(auth()->user(), 'user.suspended', $user);

        return back()->with('status', 'Conta suspensa.');
    }
}