<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cart' => [
                'required',
                'array'
            ],
            'payment' => [
                'required',
                'string',
                'in:tunai,qris'
            ]
        ];
    }
}
