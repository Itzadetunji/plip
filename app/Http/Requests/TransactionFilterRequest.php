<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionFilterRequest extends FormRequest
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
            "narration" => "nullable|string",
            "paginate"  => "nullable|boolean",
            "limit"     => "nullable|integer",
            "type"      => "nullable|string",
            "start"     => "nullable|date_format:d-m-Y",
            "end"       => "nullable|date_format:d-m-Y",
        ];
    }
}
