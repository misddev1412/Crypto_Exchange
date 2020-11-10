<?php

namespace App\Http\Controllers\Api\PaymentMethods;

use App\Http\Controllers\Controller;
use App\Models\BankAccount\BankAccount;
use App\Models\Coin\Coin;

class PaymentMethodController extends Controller
{
    public function __invoke(Coin $coin)
    {
        $availablePaymentMethods = [];
        $selectedSystemBanks = [];

        if (is_array($coin->api['selected_apis'])) {
            foreach ($coin->api['selected_apis'] as $apiKey) {
                $availablePaymentMethods[$apiKey] = coin_apis($apiKey);
            }
        } else {
            $availablePaymentMethods[$coin->api['selected_apis']] = coin_apis($coin->api['selected_apis']);
        }

        if (
            $coin->type === COIN_TYPE_FIAT &&
            is_array($coin->api['selected_banks']) &&
            !empty($coin->api['selected_banks']) &&
            is_array($coin->api['selected_apis']) &&
            in_array(API_BANK, $coin->api['selected_apis'])
        ) {

            $selectedBanks = BankAccount::whereIn('id', $coin->api['selected_banks'])
                ->with('country')
                ->get();

            foreach ($selectedBanks as $selectedBank) {
                $selectedSystemBanks[] = [
                    "id" => $selectedBank->id,
                    "country" => $selectedBank->country->name,
                    "bankName" => $selectedBank->bank_name,
                    "iban" => $selectedBank->iban,
                    "swift" => $selectedBank->swift,
                    "referenceNumber" => $selectedBank->reference_number,
                    "accountHolder" => $selectedBank->account_holder,
                    "bankAddress" => $selectedBank->bank_address,
                    "accountHolderAddress" => $selectedBank->account_holder_address,
                    "isActive" => $selectedBank->is_active,
                ];
            }
        }

        $response = [
            'availablePaymentMethods' => $availablePaymentMethods,
        ];

        if (is_array($coin->api['selected_apis']) && in_array(API_BANK, $coin->api['selected_apis'])) {
            $response['selectedSystemBanks'] = $selectedSystemBanks;
        }

        return [
            RESPONSE_STATUS_KEY => true,
            RESPONSE_DATA => $response,
        ];
    }
}
