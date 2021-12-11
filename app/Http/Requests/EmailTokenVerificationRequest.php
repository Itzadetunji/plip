<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EmailTokenVerificationRequest extends FormRequest
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
            'email' => "email|required",
            'token' => [
                "required",
                'digits:6',
                Rule::exists('email_verifications')->where(function ($query) {
                    return $query->where('email', request()->email)
                        ->where('token', request()->token);
                }),
            ],
        ];
    }

    public function messages()
    {
        return [
            "token.exists" => __("auth.token")
        ];
    }
}
