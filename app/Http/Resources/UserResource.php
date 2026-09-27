<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Public-facing creator profile. Never exposes email, birth_date,
 * age_confirmed, role, is_active, asaas_customer_id or internal ids.
 */
class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'display_name' => $this->creatorProfile?->display_name,
            'tagline' => $this->creatorProfile?->tagline,
            'avatar_url' => $this->profile?->avatar_path
                ? app(\App\Services\MediaService::class)->avatarUrl($this->profile->avatar_path)
                : null,
            'is_verified_creator' => $this->isVerifiedCreator(),
            'categories' => $this->whenLoaded('creatorProfile', fn () => $this->creatorProfile?->categories->pluck('name')),
        ];
    }
}