<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VerificationStatus;
use App\Http\Controllers\Controller;
use App\Models\CreatorProfile;
use App\Models\CreatorVerification;
use App\Models\User;
use App\Services\AuditService;
use App\Services\CreatorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CreatorController extends Controller
{
    public function __construct(
        protected AuditService $audit,
        protected CreatorService $creators,
    ) {
    }

    public function index(Request $request): View
    {
        $verifications = CreatorVerification::query()
            ->with('user.profile')
            ->when($request->query('status') && in_array($request->query('status'), ['pending', 'approved', 'rejected'], true), fn ($q) => $q->where('status', $request->query('status')))
            ->latest()
            ->paginate(20);

        return view('admin.creators.index', compact('verifications'));
    }

    public function show(CreatorVerification $verification): View
    {
        $verification->load(['user.profile', 'user.creatorProfile']);

        return view('admin.creators.show', compact('verification'));
    }

    public function document(CreatorVerification $verification): RedirectResponse
    {
        $this->authorize('manage', [User::class, $verification->user]);

        abort_if(! $verification->document_path, 404);

        $path = Storage::disk('private')->path($verification->document_path);

        return response()->download($path, basename($verification->document_path));
    }

    public function approve(CreatorVerification $verification): RedirectResponse
    {
        $this->authorize('manage', [User::class, $verification->user]);

        $this->creators->approveVerification($verification, auth()->user());

        return back()->with('status', 'Creator verificado e habilitado para monetizar.');
    }

    public function reject(Request $request, CreatorVerification $verification): RedirectResponse
    {
        $this->authorize('manage', [User::class, $verification->user]);

        $request->validate(['reason' => ['required', 'string', 'max:500']]);

        $this->creators->rejectVerification($verification, auth()->user(), $request->string('reason'));

        return back()->with('status', 'Verificação rejeitada.');
    }

    public function feature(CreatorProfile $profile): RedirectResponse
    {
        $this->authorize('manage', [User::class, $profile->user]);

        $profile->update(['is_featured' => ! $profile->is_featured]);

        $this->audit->log(auth()->user(), 'creator.featured_toggled', $profile, null, ['is_featured' => $profile->is_featured]);

        return back()->with('status', 'Creator atualizado.');
    }
}