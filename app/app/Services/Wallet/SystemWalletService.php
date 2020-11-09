<?php

namespace App\Services\Wallet;

use App\Models\Core\User;
use App\Models\Wallet\Wallet;
use App\Services\Logger\Logger;
use App\Services\Referral\ReferralService;
use Exception;
use Illuminate\Support\Facades\DB;

class SystemWalletService
{
    public function addFee(User $referralUser, string $coin, float $systemFee, bool $applyReferralBonus = false): bool
    {

        try {
            $systemWallet = Wallet::where('symbol', $coin)
                ->where('is_system_wallet', ACTIVE)
                ->first();

            if (empty($systemWallet)) {
                throw new Exception(__("System wallet could not found."));
            }

            $actualSystemFee = $systemFee;
            //Check referrer and referrer user
            if (
                settings('referral') == ACTIVE &&
                $applyReferralBonus &&
                $referralUser->referrer
            ) {
                //Increment referral amount to correspond the referral user wallet
                $actualSystemFee = app(ReferralService::class)->addEarning($referralUser, $systemFee, $coin);
            }
            //Increment system fee to system wallet
            if (!$systemWallet->increment('primary_balance', $actualSystemFee)) {
                throw new Exception(__("Failed to update system fee to system wallet"));
            }
        } catch (Exception $exception) {
            Logger::error($exception, "[FAILED][SystemWalletService][addFee]");
            return false;
        }
        return true;
    }

    public function subtractFee(string $coin, float $systemFee): bool
    {

        try {
            $systemWallet = Wallet::where('symbol', $coin)
                ->where('is_system_wallet', ACTIVE)
                ->first();

            if (empty($systemWallet)) {
                throw new Exception(__("System wallet could not found."));
            }

            //Decrement system fee to system wallet
            if (!$systemWallet->decrement('primary_balance', $systemFee)) {
                throw new Exception(__("Failed to update system fee to system wallet"));
            }
        } catch (Exception $exception) {
            Logger::error($exception, "[FAILED][SystemWalletService][subtractFee]");
            return false;
        }
        return true;
    }
}
