<?php

namespace App\Http\Requests\Coin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CoinPairRequest extends FormRequest
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
        $coinPairRequest = [
            'trade_coin' => [
                'required',
                Rule::exists('coins', 'symbol')->where('is_active', ACTIVE)
            ],
            'base_coin' => [
                'required',
                'different:trade_coin',
                Rule::exists('coins', 'symbol')->where('is_active', ACTIVE)
            ],
            'last_price' => [
                'required',
                'numeric',
                'between:0.00000001, 99999999999.99999999'
            ],
            'is_active' => [
                'required',
                Rule::in(array_keys(active_status()))
            ],
        ];

        if ($this->isMethod('POST')) {
            $coinPairRequest['is_default'] = [
                'required',
                Rule::in(array_keys(active_status()))
            ];
        }

        return $coinPairRequest;
    }
}
