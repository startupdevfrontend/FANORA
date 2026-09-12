<?php

namespace App\Services;

use App\Enums\VerificationStatus;
use App\Models\CreatorProfile;
use App\Models\CreatorVerification;
use App\Models\User;
use App\Notifications\CreatorVerificationApprovedNotification;
use App\Notifications\CreatorVerificationRejectedNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreatorService
{
    public function __construct(
        protected AuditService $audit,
        protected MediaService $media,
    ) {
    }

    /**
     * Creates (or updates) the public creator profile.
     */
    public function createOrUpdateProfile(User $user, array $data, ?array $categoryIds = []): CreatorProfile
    {
        $profile = CreatorProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'display_name' => $data['display_name'] ?? null,
                'tagline' => $data['tagline'] ?? null,
                'subscription_price_cents' => $data['subscription_price_cents'] ?? null,
                'instagram' => $data['instagram'] ?? null,
                'tiktok' => $data['tiktok'] ?? null,
                'twitter' => $data['twitter'] ?? null,
                'youtube' => $data['youtube'] ?? null,
            ]
        );

        if (! empty($categoryIds)) {
            $profile->categories()->sync($categoryIds);
        }

        $this->audit->log($user, 'creator_profile.updated', $profile);

        return $profile;
    }

    /**
     * Submits a creator verification request (documents are stored privately).
     */
    public function submitVerification(User $user, UploadedFile $document, string $documentType, ?string $notes = null): CreatorVerification
    {
        abort_if($user->isVerifiedCreator(), 422, 'Creator já verificado.');

        $path = null;

        if ($document) {
            // SECURITY: use guessExtension + uuid, not time + client extension (prevents predictable path & double-ext)
            $ext = $document->guessExtension() ?: strtolower($document->getClientOriginalExtension());
            $ext = in_array($ext, ['pdf','jpg','jpeg','png'], true) ? ($ext === 'jpeg' ? 'jpg' : $ext) : 'bin';
            $name = 'doc_'.\Illuminate\Support\Str::uuid()->toString().'.'.$ext;
            $path = $document->storeAs('verifications/'.$user->id, $name, 'private');
        }

        $verification = CreatorVerification::create([
            'user_id' => $user->id,
            'status' => VerificationStatus::Pending->value,
            'document_type' => $documentType,
            'document_path' => $path,
            'notes' => $notes,
            'submitted_at' => now(),
        ]);

        $this->audit->log($user, 'creator_verification.submitted', $verification);

        return $verification;
    }

    public function approveVerification(CreatorVerification $verification, User $admin): CreatorVerification
    {
        DB::transaction(function () use ($verification, $admin) {
            $old = $verification->only(['status']);

            $verification->update([
                'status' => VerificationStatus::Approved->value,
                'rejected_reason' => null,
                'reviewed_at' => now(),
            ]);

            $verification->user->creatorProfile?->forceFill([
                'verification_status' => VerificationStatus::Approved->value,
                'rejection_reason' => null,
            ])->save();

            $verification->user->notify(new CreatorVerificationApprovedNotification($verification->user));

            $this->audit->log($admin, 'creator_verification.approved', $verification, $old, $verification->only(['status']));
        });

        return $verification->fresh();
    }

    public function rejectVerification(CreatorVerification $verification, User $admin, string $reason): CreatorVerification
    {
        DB::transaction(function () use ($verification, $admin, $reason) {
            $old = $verification->only(['status']);

            $verification->update([
                'status' => VerificationStatus::Rejected->value,
                'rejected_reason' => $reason,
                'reviewed_at' => now(),
            ]);

            $verification->user->creatorProfile?->forceFill([
                'verification_status' => VerificationStatus::Rejected->value,
                'rejection_reason' => $reason,
            ])->save();

            $verification->user->notify(new CreatorVerificationRejectedNotification($verification->user, $reason));

            $this->audit->log($admin, 'creator_verification.rejected', $verification, $old, $verification->only(['status', 'rejected_reason']));
        });

        return $verification->fresh();
    }

    public function deleteVerificationDocument(CreatorVerification $verification): void
    {
        if ($verification->document_path) {
            Storage::disk('private')->delete($verification->document_path);
        }
    }
}