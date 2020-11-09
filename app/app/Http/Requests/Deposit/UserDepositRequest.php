<?php

namespace App\Http\Requests\Deposit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserDepositRequest extends FormRequest
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
            'amount' => [
                'required',
                'numeric',
                'between:0.01, 99999999999.99',
            ],
            'api' => [
                'required',
                Rule::in(array_keys(fiat_apis())),
            ],
            'bank_account_id' => [
                'required_if:api,' . API_BANK,
                Rule::exists('bank_accounts', 'id')
                    ->where("is_active", ACTIVE)
                    ->where('user_id', Auth::id()),
            ],
            'deposit_policy' => [
                'accepted',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'api' => __('payment method')
        ];
    }
}
