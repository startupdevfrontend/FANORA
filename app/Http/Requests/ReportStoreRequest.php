<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', Rule::in([
                'illegal_content',
                'spam',
                'fraud',
                'harassment',
                'copyright',
                'minor',
                'other',
            ])],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }
}