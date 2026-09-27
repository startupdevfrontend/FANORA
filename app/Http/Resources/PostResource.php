<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Public, safe post representation for the API.
 */
class PostResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $visibleMedia = $this->media->map(function ($media) {
            return [
                'id' => $media->id,
                'type' => $media->media_type,
                'mime_type' => $media->mime_type,
                'url' => app(\App\Services\MediaService::class)->temporaryUrl($media->file_path),
            ];
        });

        return [
            'id' => $this->id,
            'body' => $this->body,
            'visibility' => $this->visibility,
            'published_at' => $this->published_at?->toIso8601String(),
            'author' => new UserResource($this->whenLoaded('user')),
            'media' => $this->relationLoaded('media') ? $visibleMedia : [],
        ];
    }
}