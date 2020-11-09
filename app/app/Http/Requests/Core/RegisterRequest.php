<?php

namespace App\Http\Requests\Core;

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
        $validation = [
            "first_name" => "required|alpha_space|between:2,255",
            "last_name" => "required|alpha_space|between:2,255",
            "email" => "required|email|unique:users,email|between:5,255",
            "username" => "required|unique:users,username|max:255",
            'password' => 'required|between:6,32|same:password_confirmation',
            'password_confirmation' => 'required',
            "check_agreement" => "required",
        ];

        if( env('APP_ENV') != 'local' && settings('display_google_captcha') == ACTIVE )
        {
            $validation['g-recaptcha-response'] = 'required|captcha';
        }

        return $validation;
    }

    public function attributes()
    {
        return ['g-recaptcha-response' => 'google captcha'];
    }
}
