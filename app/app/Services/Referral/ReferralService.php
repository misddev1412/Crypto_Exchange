<?php

namespace App\Services\Referral;

use App\Models\Core\User;
use App\Models\Referral\ReferralEarning;
use App\Models\Wallet\Wallet;
use App\Services\Logger\Logger;
use Exception;

class ReferralService
{
    public function addEarning(User $referralUser, float $systemFee, string $coin): float
    {
        $actualSystemFee = $systemFee;

        try {
            $referrerUserWallet = Wallet::where('user_id', $referralUser->referrer_id)
                ->where('symbol', $coin)
                ->withoutSystemWallet()
                ->first();
            if (!empty($referrerUserWallet)) {
                $referralAmount = calculate_referral_amount($systemFee);
                $actualSystemFee = bcsub($systemFee, $referralAmount);

                if (!$referrerUserWallet->increment('primary_balance', $referralAmount)) {
                    throw new Exception(__("Cannot increment referral bonus to referrer user wallet"));
                }

                $referralEarning = ReferralEarning::create([
                    'referrer_user_id' => $referralUser->referrer_id,
                    'referral_user_id' => $referralUser->id,
                    'symbol' => $coin,
                    'amount' => $referralAmount,
                ]);

                if (empty($referralEarning)) {
                    throw new Exception(__("Cannot create referral earning."));
                }
            }
        } catch (Exception $exception) {
            Logger::error($exception, "[FAILED][ReferralService][addEarning]");
            return $systemFee;
        }
        return $actualSystemFee;
    }
}
