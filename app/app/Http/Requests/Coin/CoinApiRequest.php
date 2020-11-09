<?php

namespace App\Http\Requests\Coin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CoinApiRequest extends FormRequest
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
        $coin = $this->route('coin');
        $paymentServices = ($coin->type === COIN_TYPE_FIAT) ? fiat_apis() : crypto_apis();
        $rules = [
            'api' => ['required'],
            'api.*' => Rule::in(array_keys($paymentServices))
        ];

        if ($coin->type == COIN_TYPE_FIAT) {
            if (in_array(API_BANK, $this->get('api'))) {
                $rules['banks'] = ['required'];
                $rules['banks.*'] = [
                    Rule::exists('bank_accounts', 'id')->where(function ($query) {
                        $query->whereNull('user_id');
                    })
                ];
            }
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'api.*' => __('api'),
            'banks.*' => __('bank(s)'),
        ];
    }
}
