<?php


namespace App\Services\Deposit;


use App\Jobs\Deposit\DepositProcessJob;
use App\Models\Core\Notification;
use App\Models\Deposit\WalletDeposit;
use App\Models\Wallet\Wallet;
use App\Services\Logger\Logger;
use App\Services\Wallet\SystemWalletService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepositService
{
    private $wallet;

    public function deposit(array $depositData)
    {
        DB::beginTransaction();
        try {
            $this->setWallet($depositData);

            if (empty($this->wallet)) {
                throw new Exception("No wallet found with this address: {$depositData['address']}");
            }

            $systemFee = calculate_deposit_system_fee(
                $depositData['amount'],
                $this->wallet->coin->deposit_fee,
                $this->wallet->coin->deposit_fee_type
            );

            $actualAmount = bcsub($depositData['amount'], $systemFee);

            $deposit = $this->getDeposit($depositData);

            if (empty($deposit)) {
                $params = [
                    'user_id' => $this->wallet->user_id,
                    'wallet_id' => $this->wallet->id,
                    'symbol' => $this->wallet->symbol,
                    'address' => $depositData['address'],
                    'amount' => $depositData['amount'],
                    'system_fee' => $systemFee,
                    'txn_id' => $depositData['txn_id'],
                    'api' => $depositData['api'],
                    'status' => STATUS_PENDING,
                ];

                $deposit = WalletDeposit::create($params);

            }

            if ($deposit->status === STATUS_PENDING && $depositData['status'] === STATUS_COMPLETED) {
                //Update wallet primary balance
                if (!$this->updateWalletBalance($actualAmount, $systemFee)) {
                    throw new Exception("Cannot update wallet balance");
                }
                //Update deposit status
                if (!$deposit->update(['status' => $depositData['status'], 'system_fee' => $systemFee])) {
                    throw new Exception("Cannot update deposit status");
                }
                // User Notification
                Notification::create([
                    'user_id' => $deposit->user_id,
                    'message' => __("You've just received :amount :coin", [
                        'amount' => $actualAmount,
                        'coin' => $deposit->symbol,
                    ]),
                ]);

            }
        } catch (Exception $exception) {
            DB::rollBack();
            Logger::error($exception, "[FAILED][DepositService][deposit]");
        }
        DB::commit();
    }

    private function setWallet(array $depositData)
    {
        if (isset($depositData['wallet_id'])) {
            $conditions = [
                'id' => $depositData['wallet_id'],
                'symbol' => $depositData['symbol'],
            ];
        } else {
            $conditions = [
                'address' => $depositData['address'],
                'symbol' => $depositData['symbol'],
            ];
        }

        $this->wallet = Wallet::where($conditions)->first();
    }

    private function getDeposit(array $depositData)
    {
        if (isset($depositData['id'])) {
            $deposit = WalletDeposit::find($depositData['id']);
        } else {
            $deposit = WalletDeposit::where('symbol', $depositData['symbol'])
                ->where('address', $depositData['address'])
                ->where('txn_id', $depositData['txn_id'])
                ->first();
        }

        if (!empty($deposit) && bccomp($deposit->amount, $depositData['amount']) > 0) {
            $deposit->update(['amount' => $depositData['amount']]);
            $deposit = $deposit->refresh();
        }


        return $deposit;
    }

    private function updateWalletBalance(float $amount, float $systemFee): bool
    {
        //Increment user wallet
        if (!$this->wallet->increment('primary_balance', $amount)) {
            return false;
        }

        if (bccomp($systemFee, '0') > 0) {
            //Increment system wallet
            if (!app(SystemWalletService::class)->addFee($this->wallet->user, $this->wallet->symbol, $systemFee)) {
                return false;
            }
        }
        return true;
    }

    public function show(WalletDeposit $deposit)
    {
        $data['deposit'] = $deposit;
        $data['title'] = __("Deposit Details");
        return view('deposit.admin.show', $data);
    }

    public function approve(WalletDeposit $deposit)
    {
        if ($deposit->status != STATUS_REVIEWING) {
            return redirect()
                ->back()
                ->with(RESPONSE_TYPE_ERROR, __("Cannot approve the deposit."));
        }

        $deposit->txn_id = Str::random(36);
        $deposit->status = STATUS_PENDING;

        DB::beginTransaction();
        try {
            if ($deposit->update()) {
                $depositData = [
                    'id' => $deposit->id,
                    'wallet_id' => $deposit->wallet_id,
                    'symbol' => $deposit->symbol,
                    'amount' => $deposit->amount,
                    'type' => TRANSACTION_DEPOSIT,
                    'status' => STATUS_COMPLETED,
                    'api' => API_BANK,
                ];
                DepositProcessJob::dispatch($depositData);
            }

            if ($deposit->bankAccount->is_verified == UNVERIFIED) {
                $deposit->bankAccount()->update(['is_verified' => VERIFIED]);
            }
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with(RESPONSE_TYPE_ERROR, __("Failed to approve the deposit."));
        }
        DB::commit();

        return redirect()
            ->route(replace_current_route_action('show'), $deposit->id)
            ->with(RESPONSE_TYPE_SUCCESS, __("The deposit has been approved successfully."));
    }

    public function cancel(WalletDeposit $deposit)
    {
        if ($deposit->status != STATUS_REVIEWING) {
            return redirect()
                ->back()
                ->with(RESPONSE_TYPE_ERROR, __("Cannot cancel the deposit."));
        }

        if ($deposit->update(['status' => STATUS_CANCELED])) {
            return redirect()
                ->route(replace_current_route_action('show'), $deposit->id)
                ->with(RESPONSE_TYPE_SUCCESS, __("The deposit has been canceled successfully."));
        }

        return redirect()
            ->back()
            ->with(RESPONSE_TYPE_ERROR, __("Failed to cancel the deposit."));
    }
}
