<?php

namespace App\Http\Requests;

use App\Models\EmailVerification;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

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
            'first_name'    => "string|required|regex:/^[\pL\s\-]+$/u|max:150",
            'middle_name'   => "string|nullable|regex:/^[\pL\s\-]+$/u|max:150",
            'last_name'     => "string|required|regex:/^[\pL\s\-]+$/u|max:150",
            'nick_name'     => "string|nullable|max:100",
            'email'         => [
                "email",
                "required",
                "unique:users,email",
                Rule::exists('email_verifications')->where(function ($query) {
                    return $query->where('email', request()->email)
                        ->whereNotNull('email_verified_at');
                }),
            ],
            'password'      => [
                'required',
                Password::min(6)
                    ->mixedCase()
                    ->letters()
                    ->numbers(),
            ],
            'phone'         => "phone|nullable|unique:users,phone",
            'phone_country' => "required_with:phone",
            'device_name'   => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            "email.exists" => "Email not verified!"
        ];
    }
}
