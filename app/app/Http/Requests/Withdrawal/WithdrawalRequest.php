<?php


namespace App\Http\Requests\Withdrawal;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class WithdrawalRequest extends FormRequest
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
        $wallet = $this->route('wallet');
        $rules = [
            'address' => [
                "required"
            ],
            'amount' => [
                "required",
                "numeric",
                "min:{$wallet->coin->minimum_withdrawal_amount}"
            ],
            'withdrawal_policy' => [
                'accepted'
            ]
        ];

        if ($wallet->coin->type === COIN_TYPE_FIAT) {
            $rules['api'] = ['required', Rule::in(array_keys(fiat_apis()))];
            if ($this->get('api') === API_BANK) {
                $rules['bank_account_id'] = [
                    'required',
                    Rule::exists('bank_accounts', 'id')
                        ->where('is_active', ACTIVE)
                        ->where('is_verified', VERIFIED)
                ];
                unset($rules['address']);
            }
        }

        return $rules;
    }
}
