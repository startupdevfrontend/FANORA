<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatorProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'display_name' => ['nullable', 'string', 'max:120'],
            'tagline' => ['nullable', 'string', 'max:300'],
            'subscription_price_cents' => [
                'nullable',
                'integer',
                'in:'.implode(',', config('fanora.allowed_subscription_prices')),
            ],
            'categories' => ['nullable', 'array', 'max:5'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'tiktok' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'youtube' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'subscription_price_cents.in' => 'O preço escolhido não está entre as opções permitidas.',
        ];
    }
}