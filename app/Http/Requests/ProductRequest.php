<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ProductRequest extends FormRequest
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
        $product = $this->route('product');
        $productId = $product ? $product->id : null;

        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'cost_price' => 'nullable|numeric|min:0|max:999999.99',
            'sale_price' => 'nullable|numeric|min:0|max:999999.99',
            'stock' => 'required|integer|min:0|max:999999',
            'min_stock' => 'nullable|integer|min:0|max:999999',
            'max_stock' => 'nullable|integer|min:0|max:999999',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'sku' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($productId),
            ],
            'barcode' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products', 'barcode')->ignore($productId),
            ],
            'is_active' => 'boolean',
            'weight' => 'nullable|numeric|min:0|max:999.999',
            'height' => 'nullable|numeric|min:0|max:999.99',
            'width' => 'nullable|numeric|min:0|max:999.99',
            'length' => 'nullable|numeric|min:0|max:999.99',
            'specifications' => 'nullable|array',
            'specifications.*' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|string|url',
            'last_purchase_date' => 'nullable|date',
            'last_sale_date' => 'nullable|date',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome do produto é obrigatório.',
            'name.max' => 'O nome do produto não pode ter mais de 255 caracteres.',
            'description.max' => 'A descrição não pode ter mais de 1000 caracteres.',
            'price.required' => 'O preço é obrigatório.',
            'price.numeric' => 'O preço deve ser um número.',
            'price.min' => 'O preço deve ser maior que zero.',
            'price.max' => 'O preço não pode ser maior que R$ 999.999,99.',
            'cost_price.numeric' => 'O preço de custo deve ser um número.',
            'cost_price.min' => 'O preço de custo deve ser maior que zero.',
            'cost_price.max' => 'O preço de custo não pode ser maior que R$ 999.999,99.',
            'sale_price.numeric' => 'O preço de venda deve ser um número.',
            'sale_price.min' => 'O preço de venda deve ser maior que zero.',
            'sale_price.max' => 'O preço de venda não pode ser maior que R$ 999.999,99.',
            'stock.required' => 'O estoque é obrigatório.',
            'stock.integer' => 'O estoque deve ser um número inteiro.',
            'stock.min' => 'O estoque deve ser maior ou igual a zero.',
            'stock.max' => 'O estoque não pode ser maior que 999.999.',
            'min_stock.integer' => 'O estoque mínimo deve ser um número inteiro.',
            'min_stock.min' => 'O estoque mínimo deve ser maior ou igual a zero.',
            'min_stock.max' => 'O estoque mínimo não pode ser maior que 999.999.',
            'max_stock.integer' => 'O estoque máximo deve ser um número inteiro.',
            'max_stock.min' => 'O estoque máximo deve ser maior ou igual a zero.',
            'max_stock.max' => 'O estoque máximo não pode ser maior que 999.999.',
            'category_id.required' => 'A categoria é obrigatória.',
            'category_id.exists' => 'A categoria selecionada não existe.',
            'brand_id.exists' => 'A marca selecionada não existe.',
            'sku.unique' => 'Este SKU já está em uso.',
            'sku.max' => 'O SKU não pode ter mais de 100 caracteres.',
            'barcode.unique' => 'Este código de barras já está em uso.',
            'barcode.max' => 'O código de barras não pode ter mais de 100 caracteres.',
            'weight.numeric' => 'O peso deve ser um número.',
            'weight.min' => 'O peso deve ser maior que zero.',
            'weight.max' => 'O peso não pode ser maior que 999,999 kg.',
            'height.numeric' => 'A altura deve ser um número.',
            'height.min' => 'A altura deve ser maior que zero.',
            'height.max' => 'A altura não pode ser maior que 999,99 cm.',
            'width.numeric' => 'A largura deve ser um número.',
            'width.min' => 'A largura deve ser maior que zero.',
            'width.max' => 'A largura não pode ser maior que 999,99 cm.',
            'length.numeric' => 'O comprimento deve ser um número.',
            'length.min' => 'O comprimento deve ser maior que zero.',
            'length.max' => 'O comprimento não pode ser maior que 999,99 cm.',
            'specifications.array' => 'As especificações devem ser um array.',
            'images.array' => 'As imagens devem ser um array.',
            'images.*.url' => 'Cada imagem deve ser uma URL válida.',
            'last_purchase_date.date' => 'A data da última compra deve ser uma data válida.',
            'last_sale_date.date' => 'A data da última venda deve ser uma data válida.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'description' => 'descrição',
            'price' => 'preço',
            'cost_price' => 'preço de custo',
            'sale_price' => 'preço de venda',
            'stock' => 'estoque',
            'min_stock' => 'estoque mínimo',
            'max_stock' => 'estoque máximo',
            'category_id' => 'categoria',
            'brand_id' => 'marca',
            'sku' => 'SKU',
            'barcode' => 'código de barras',
            'is_active' => 'ativo',
            'weight' => 'peso',
            'height' => 'altura',
            'width' => 'largura',
            'length' => 'comprimento',
            'specifications' => 'especificações',
            'images' => 'imagens',
            'last_purchase_date' => 'data da última compra',
            'last_sale_date' => 'data da última venda',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean barcode (remove non-numeric characters)
        if ($this->has('barcode') && $this->barcode) {
            $this->merge([
                'barcode' => preg_replace('/[^0-9]/', '', $this->barcode)
            ]);
        }

        // Set default values
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
            'stock' => $this->stock ?? 0,
            'min_stock' => $this->min_stock ?? 0,
        ]);
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        // Log validation errors for debugging
        \Illuminate\Support\Facades\Log::warning('Validação de produto falhou', [
            'errors' => $validator->errors()->toArray(),
            'data' => $this->except(['password', 'password_confirmation']),
            'user_id' => Auth::id() ?? null,
        ]);

        parent::failedValidation($validator);
    }
}
