<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
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
                'string',
            ],
            'phone' => [
                'nullable',
                'string',
            ],
            'notes' => [
                'nullable',
                'string',
            ],
            'payment_method' => [
                'required',
                'string',
                'in:tunai,qris',
            ],
            'payment_status' => [
                'required',
                'integer',
                'in:0,1',
            ],
            'cart' => [
                'required',
                'array',
            ],
        ];
    }
}
