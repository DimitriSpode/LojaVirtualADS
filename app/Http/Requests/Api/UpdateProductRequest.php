<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'type_id' => 'required|exists:types,id',
            'image' => 'nullable|string|max:255',
        ];
    }
}
