<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->route('user');
        $userId = $user ? $user->id : null;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
        ];

        // Password rules for create or when password is provided
        if (!$user || $this->filled('password')) {
            $rules['password'] = [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                Password::defaults(),
            ];
        }

        // Role assignment rules
        if ($this->filled('roles')) {
            $rules['roles'] = ['array'];
            $rules['roles.*'] = ['string', 'exists:roles,name'];
        }

        // Email verification rules
        if ($this->filled('email_verified_at')) {
            $rules['email_verified_at'] = ['nullable', 'date'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser um endereço válido.',
            'email.unique' => 'Este email já está em uso.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'roles.array' => 'As roles devem ser fornecidas como uma lista.',
            'roles.*.exists' => 'Uma das roles selecionadas não existe.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'email' => 'email',
            'password' => 'senha',
            'roles' => 'roles',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Normalize email to lowercase
        if ($this->filled('email')) {
            $this->merge([
                'email' => strtolower($this->email),
            ]);
        }

        // Handle email verification
        if ($this->boolean('is_active')) {
            $this->merge([
                'email_verified_at' => now(),
            ]);
        } elseif ($this->filled('is_active') && !$this->boolean('is_active')) {
            $this->merge([
                'email_verified_at' => null,
            ]);
        }
    }
}
