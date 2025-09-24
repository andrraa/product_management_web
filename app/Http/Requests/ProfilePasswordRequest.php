<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfilePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'password' => [
                'required',
                'string'
            ],
            'password_confirmation' => [
                'required',
                'string',
                'same:password'
            ]
        ];
    }
}
