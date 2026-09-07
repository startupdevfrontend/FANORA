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

        return [
            'body' => ['required', 'string', 'max:5000'],
            'visibility' => ['required', Rule::in(['public', 'subscribers_only'])],
            'media' => ['nullable', 'array', 'max:10'],
            'media.*' => [
                'file',
                'max:'.$config['video_max_bytes'],
            ],
        ];
    }
}