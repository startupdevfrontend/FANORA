<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Public catalog entry for a verified creator profile.
 */
class CreatorProfileResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this->user;

        return [
            'id' => $user?->id,
            'name' => $user?->name,
            'username' => $user?->username,
            'display_name' => $this->display_name,
            'tagline' => $this->tagline,
            'subscription_price_cents' => $this->subscription_price_cents,
            'avatar_url' => $user?->profile?->avatar_path
                ? app(\App\Services\MediaService::class)->avatarUrl($user->profile->avatar_path)
                : null,
            'subscriber_count' => $this->subscriber_count,
            'categories' => $this->whenLoaded('categories', fn () => $this->categories->map(fn ($c) => [
                'slug' => $c->slug,
                'name' => $c->name,
            ])),
            'social' => [
                'instagram' => $this->instagram,
                'tiktok' => $this->tiktok,
                'twitter' => $this->twitter,
                'youtube' => $this->youtube,
            ],
        ];
    }
}