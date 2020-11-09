<?php

namespace App\Http\Requests\Core;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PreferenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'display_language' => [
                Rule::exists('languages', 'sort_code')->where('is_active', ACTIVE)
            ],
            'default_coin_pair' => [
                Rule::exists('coin_pairs', 'name')->where('is_active', ACTIVE)
            ]
        ];
    }
}
