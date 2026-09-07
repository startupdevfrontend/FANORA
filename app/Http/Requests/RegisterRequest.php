<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'username' => ['required', 'string', 'lowercase', 'alpha_dash', 'min:3', 'max:30', Rule::unique('users')],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
            'birth_date' => ['required', 'date', 'before_or_equal:'.now()->subYears(18)->toDateString()],
            'terms' => ['accepted'],
            'privacy' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'birth_date.before_or_equal' => 'Você precisa ter pelo menos 18 anos para criar uma conta.',
            'birth_date.required' => 'Informe sua data de nascimento para confirmar maioridade.',
            'terms.accepted' => 'Você precisa aceitar os Termos de Uso.',
            'privacy.accepted' => 'Você precisa aceitar a Política de Privacidade.',
            'username.unique' => 'Este nome de usuário já está em uso.',
            'username.alpha_dash' => 'Use apenas letras, números, traço e sublinhado no usuário.',
        ];
    }
}