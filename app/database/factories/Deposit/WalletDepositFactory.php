<?php

namespace Database\Factories\Deposit;

use App\Models\Core\User;
use App\Models\Deposit\WalletDeposit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WalletDepositFactory extends Factory
{
    protected $model = WalletDeposit::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first();
        $wallet = $user->wallets()->inRandomOrder()->with('coin')->first();
        $bankAccountId = null;
        $systemBankAccountId = null;
        $address = $this->faker->shuffleString('tb1qur5j7297t0n8w0gtjke0jyl2vamfvs5rn52vuv');
        $amount = $wallet->coin->type === COIN_TYPE_FIAT ? $this->faker->randomFloat(8, 500, 2000) : $this->faker->randomFloat(8, 0.001, 0.99);
        if (is_array($wallet->coin->api['selected_apis']) && in_array(API_BANK, $wallet->coin->api['selected_apis'])) {
            $bankAccountId = $user->banks()->inRandomOrder()->first()->id;
            $systemBankAccountId = $this->faker->randomElement($wallet->coin->api['selected_banks']);
            $address = null;
        }
        return [
            'user_id' => $user->id,
            'wallet_id' => $wallet->id,
            'bank_account_id' => $bankAccountId,
            'symbol' => $wallet->symbol,
            'system_bank_account_id' => $systemBankAccountId,
            'address' => $address,
            'amount' => $amount,
            'system_fee' => bcdiv(bcmul($amount, '2'), '100'),
            'txn_id' => $wallet->coin->type === COIN_TYPE_FIAT ? Str::uuid()->toString() : $this->faker->shuffleString('5ddb7258688c24344f2b86769ff84bfe3041fdf5c2a1d4761ee727dbd77bae7e'),
            'api' => $wallet->coin->type === COIN_TYPE_FIAT ? $this->faker->randomElement($wallet->coin->api['selected_apis']) : $wallet->coin->api['selected_apis'],
            'status' => $this->faker->boolean(60) ? STATUS_COMPLETED : ($bankAccountId ? STATUS_REVIEWING : STATUS_COMPLETED),
            'created_at' => $this->faker->dateTimeThisMonth,
            'updated_at' => $this->faker->dateTimeThisMonth
        ];
    }
}
