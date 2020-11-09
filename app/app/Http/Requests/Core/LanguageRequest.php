<?php

namespace App\Http\Requests\Core;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LanguageRequest extends FormRequest
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
            'name' => [
                'required',
                Rule::unique('languages')->ignore($this->route()->parameter('language')),
            ],
            'short_code' => [
                'required',
                'min:2',
                'max:2',
                Rule::unique('languages')->ignore($this->route()->parameter('language'))
            ],
            'icon' => 'image|max:100'
        ];
    }
}
