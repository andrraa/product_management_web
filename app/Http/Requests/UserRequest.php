<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UserRequest extends FormRequest
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
            'username' => [
                'required',
                'string',
                'unique:tbl_users,username'
            ],
            'password' => [
                'required',
                'string'
            ],
            'role' => [
                'required',
                'string',
                'in:admin,employee'
            ],
            'shift' => [
                'required',
                'string',
                'in:morning,night'
            ]
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'name' => ucwords(strtolower($this->name)),
            'username' => strtolower($this->username),
            'password' => Hash::make($this->password)
        ]);
    }
}
