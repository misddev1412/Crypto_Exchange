<?php

namespace App\Http\Requests\Kyc;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserKycVerificationRequest extends FormRequest
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
            'id_type' => [
                'required',
                Rule::in(array_keys(kyc_type())),
            ],
            'id_card_front' => [
                'required',
                'image',
                'max:2048',
            ],
            'id_card_back' => [
                Rule::requiredIf(function () {
                    return $this->get('id_type') != KYC_TYPE_PASSPORT;
                }),
                'image',
                'max:2048',
            ],
        ];
    }
}
