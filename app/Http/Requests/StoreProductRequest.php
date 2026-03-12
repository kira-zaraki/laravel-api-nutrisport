<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && ($user->id === 1 || $user->role === 'agent');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'prices' => 'required|array',
            'prices.*' => 'numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du produit est obligatoire.',
            'stock.required' => 'Le stock initial est obligatoire.',
            'stock.integer' => 'Le stock doit être un nombre entier.',
            'prices.required' => 'Les prix par site sont obligatoires.',
            'prices.array' => 'Les prix doivent être un tableau associatif site_id => prix.',
            'prices.*.numeric' => 'Chaque prix doit être un nombre valide.',
        ];
    }
}
