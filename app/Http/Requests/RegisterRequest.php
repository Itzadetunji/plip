<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => "string|required|regex:/^[\pL\s\-]+$/u|max:150",
            'middle_name' => "string|nullable|regex:/^[\pL\s\-]+$/u|max:150",
            'last_name' => "string|required|regex:/^[\pL\s\-]+$/u|max:150",
            'nick_name' => "string|nullable|regex:/^[\pL\s\-]+$/u|max:100",
            'email' => "email|required|unique:users,email",
            'password'      => [
                'required',
                Password::min(6)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
            ]
        ];
    }
}
