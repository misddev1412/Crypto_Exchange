<?php

namespace App\Http\Requests\Coin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CoinDepositRequest extends FormRequest
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
            'deposit_status' => [
                'required',
                Rule::in(array_keys(active_status()))
            ],
            'deposit_fee' => [
                'numeric',
                'min:0',
                Rule::requiredIf(function () {
                    return $this->get('deposit_status') == ACTIVE;
                }),
            ],
            'deposit_fee_type' => [
                'required',
                Rule::in(array_keys(fee_types()))
            ],
            'minimum_deposit_amount' => [
                'numeric',
                'min:0',
                Rule::requiredIf(function () {
                    return $this->route('coin')->type === COIN_TYPE_FIAT;
                }),
            ],
        ];
    }
}
