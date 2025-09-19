<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
                Rule::unique('tbl_users')
                    ->ignore($this->user->user_id ?? null, 'user_id')
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
        ]);
    }
}
