<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellGoodRequest extends FormRequest
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
            'email' => 'required|email',
            'phone' => 'required|string|min:4|max:4',
            'amount' => 'required|numeric',
        ];
    }
}
