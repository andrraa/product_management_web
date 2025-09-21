<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string'
            ],
            'price'=> [
                'required',
                'string'
            ],
            'stock' => [
                'required_if:type,food',
                'integer'
            ],
            'type' => [
                'required',
                'string',
                'in:food,billing'
            ]
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'name' => ucwords(strtolower($this->name))
        ]);
    }
}
