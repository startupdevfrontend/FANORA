<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isVerifiedCreator();
    }

    public function rules(): array
    {
        $config = config('fanora.media');
        // Laravel 'max' is kilobytes; convert bytes -> KB correctly.
        $imageMaxKb = (int) ceil($config['image_max_bytes'] / 1024);
        $videoMaxKb = (int) ceil($config['video_max_bytes'] / 1024);

        return [
            'body' => ['required', 'string', 'max:5000'],
            'visibility' => ['required', Rule::in(['public', 'subscribers_only'])],
            'media' => ['nullable', 'array', 'max:10'],
            'media.*' => [
                'file',
                // Use the larger limit (video) as unified cap; MediaService enforces type-specific limits.
                'max:'.$videoMaxKb,
                // Accept only whitelisted mime types; blocks disguised executables.
                'mimes:jpeg,jpg,png,webp,gif,mp4,webm,mov',
                // Double-check mime via extension + mimeType in MediaService.
                'mimetypes:image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/quicktime',
            ],
        ];
    }
}