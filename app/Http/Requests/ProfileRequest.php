<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
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
                Rule::unique('tbl_users', 'username')
                    ->ignore(Auth::id(), 'user_id')
            ]
        ];
    }
}
