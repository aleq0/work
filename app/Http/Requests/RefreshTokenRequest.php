<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RefreshTokenRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'refresh_token' => 'required|max:150',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return response()->error('Invalid refresh token');
    }
}
