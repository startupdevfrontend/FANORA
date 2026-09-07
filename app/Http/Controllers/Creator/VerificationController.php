<?php

namespace App\Http\Controllers\Creator;

use App\Enums\VerificationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatorVerificationRequest;
use App\Models\CreatorVerification;
use App\Services\AuditService;
use App\Services\CreatorService;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function __construct(
        protected CreatorService $creators,
        protected AuditService $audit,
        protected MediaService $media,
    ) {
    }

    public function index(): View
    {
        $user = auth()->user();
        $verification = $user->creatorVerifications()->latest()->first();

        return view('creator.verification.index', compact('user', 'verification'));
    }

    public function store(CreatorVerificationRequest $request): RedirectResponse
    {
        $user = auth()->user();

        $existingPending = CreatorVerification::where('user_id', $user->id)
            ->where('status', VerificationStatus::Pending->value)
            ->exists();

        abort_if($existingPending, 422, 'Já existe uma solicitação de verificação em análise.');

        $this->creators->submitVerification(
            $user,
            $request->file('document'),
            $request->string('document_type'),
            $request->input('notes'),
        );

        return back()->with('status', 'Solicitação de verificação enviada. Você será notificado quando for analisada.');
    }
}