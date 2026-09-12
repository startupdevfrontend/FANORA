<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Input sanitization: strip tags from text fields to prevent XSS persistence
        if ($this->has('bio')) {
            $this->merge(['bio' => strip_tags((string) $this->input('bio'))]);
        }
        if ($this->has('name')) {
            $this->merge(['name' => strip_tags((string) $this->input('name'))]);
        }
        if ($this->has('location')) {
            $this->merge(['location' => strip_tags((string) $this->input('location'))]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'username' => ['required', 'string', 'lowercase', 'alpha_dash', 'min:3', 'max:30', 'unique:users,username,'.$this->user()->id],
            'bio' => ['nullable', 'string', 'max:500'],
            'location' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'url', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'cover' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:10240'],
        ];
    }
}