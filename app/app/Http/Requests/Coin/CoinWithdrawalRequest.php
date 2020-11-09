<?php

namespace App\Http\Requests\Coin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CoinWithdrawalRequest extends FormRequest
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
            'withdrawal_status' => [
                'required',
                Rule::in(array_keys(active_status()))
            ],
            'minimum_withdrawal_amount' => [
                'required',
                'numeric',
                'decimal_scale:11,8',
                'gt:0',
                Rule::requiredIf(function () {
                    return $this->get('withdrawal_status') == ACTIVE;
                }),
            ],
            'daily_withdrawal_limit' => [
                'numeric',
                'decimal_scale:11,8',
                'gte:0',
            ],
            'withdrawal_fee' => [
                'required',
                'numeric',
                'min:0',
                'decimal_scale:6,8',
                Rule::requiredIf(function () {
                    return $this->get('withdrawal_status') == ACTIVE;
                }),
            ],
            'withdrawal_fee_type' => [
                'required',
                Rule::in(array_keys(fee_types()))
            ]
        ];
    }
}
