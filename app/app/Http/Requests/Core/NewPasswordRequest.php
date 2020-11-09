<?php

namespace App\Http\Requests\Core;

use Illuminate\Foundation\Http\FormRequest;

class NewPasswordRequest extends FormRequest
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
        $validation = [
            'new_password' => 'required|between:6,32|same:password_confirmation',
            'password_confirmation' => 'required',
        ];

        if( env('APP_ENV') != 'local' && settings('display_google_captcha') == ACTIVE )
        {
            $validation['g-recaptcha-response'] = 'required|captcha';
        }

        return $validation;
    }
}
