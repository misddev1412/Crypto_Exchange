<?php

namespace App\Http\Requests\Deposit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BankReceiptUploadRequest extends FormRequest
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
            'system_bank_id' => [
                'required',
                Rule::exists('bank_accounts', 'id')->where(function ($query) {
                    $query->whereNull('user_id')->where('is_active', ACTIVE);
                })
            ],
            'receipt' => [
                'required',
                'mimes:jpeg,jpg,png',
                'max:2048'
            ]
        ];
    }
}
