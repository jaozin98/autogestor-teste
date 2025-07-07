<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class BrandRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $brandId = $this->route('brand')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands', 'name')->ignore($brandId),
            ],
            'country_of_origin' => 'nullable|string|max:255',
            'founded_year' => [
                'nullable',
                'integer',
                'min:1800',
                'max:' . (date('Y') + 1),
            ],
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome da marca é obrigatório.',
            'name.string' => 'O nome deve ser um texto.',
            'name.max' => 'O nome da marca não pode ter mais de 255 caracteres.',
            'name.unique' => 'Esta marca já existe.',
            'country_of_origin.string' => 'O país de origem deve ser um texto.',
            'country_of_origin.max' => 'O país de origem não pode ter mais de 255 caracteres.',
            'founded_year.integer' => 'O ano de fundação deve ser um número inteiro.',
            'founded_year.min' => 'O ano de fundação deve ser maior ou igual a 1800.',
            'founded_year.max' => 'O ano de fundação não pode ser maior que o ano atual.',
            'website.url' => 'O website deve ser uma URL válida.',
            'website.max' => 'O website não pode ter mais de 255 caracteres.',
            'description.string' => 'A descrição deve ser um texto.',
            'description.max' => 'A descrição não pode ter mais de 1000 caracteres.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nome da marca',
            'country_of_origin' => 'país de origem',
            'founded_year' => 'ano de fundação',
            'website' => 'website',
            'description' => 'descrição',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean website URL (remove protocol if not provided)
        if ($this->has('website') && $this->website && !preg_match('/^https?:\/\//', $this->website)) {
            $this->merge([
                'website' => 'https://' . $this->website
            ]);
        }

        // Clean name (trim and capitalize)
        if ($this->has('name')) {
            $this->merge([
                'name' => trim(ucwords(strtolower($this->name)))
            ]);
        }

        // Clean country name (trim and capitalize)
        if ($this->has('country_of_origin')) {
            $this->merge([
                'country_of_origin' => trim(ucwords(strtolower($this->country_of_origin)))
            ]);
        }
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        // Log validation errors for debugging
        \Illuminate\Support\Facades\Log::warning('Validação de marca falhou', [
            'errors' => $validator->errors()->toArray(),
            'data' => $this->except(['password', 'password_confirmation']),
            'user_id' => Auth::id() ?? null,
        ]);

        parent::failedValidation($validator);
    }
}
