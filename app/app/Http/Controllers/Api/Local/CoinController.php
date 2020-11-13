<?php

namespace App\Http\Controllers\Api\Local;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deposit\BankReceiptUploadRequest;
use App\Http\Requests\Deposit\UserDepositRequest;
use App\Http\Requests\Deposit\TokenExchangeRequest;
use App\Models\BankAccount\BankAccount;
use App\Models\Deposit\WalletDeposit;
use App\Models\Wallet\Wallet;
use App\Services\Core\UserService;
use App\Services\Wallet\WalletService;
use App\Services\Core\FileUploadService;
use App\Services\Wallet\GenerateWalletAddressImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CoinController extends Controller
{
    public function test()
    {
        $response = ['sss' => 'ss'];
        return response()->json($response, 200);
    }

    public function depositTokenExchange(TokenExchangeRequest $request, UserService $userService, WalletService $walletService, Log $log)
    {
        $email          = $request->email;
        $phone          = $request->phone;
        $username       = $request->username;
        $amount         = $request->amount;
        $checkUser      = $userService->getDataDeposit($email, $username);
        $checkProfile   = $userService->getDataProfileDeposit($phone, $checkUser->id);
        $result         = false;
        if ($checkProfile && $checkUser) {
            try {
                $result     = $walletService->depositToWallet($checkUser->id, $amount);
            } catch (\Exception $e) {
                $log->error($e);
                return response()->json([
                    'status'    => 500,
                    'message'   => 'Something went wrong, please contact the admin'
                ]);
            }
        } else {
            return response()->json([
                'status'    => 404,
                'message'   => 'We couldn\'t find your information'
            ]);
        }
    }

    public function store(UserDepositRequest $request, Wallet $wallet)
    {
        if ($wallet->coin->type === COIN_TYPE_FIAT) {
            if (bccomp($wallet->coin->minimum_deposit_amount, $request->amount) > 0) {
                return response()->json([
                    RESPONSE_STATUS_KEY => false,
                    RESPONSE_MESSAGE_KEY => __("The deposit amount must be greater than :amount.", [
                        'amount' => $wallet->coin->minimum_deposit_amount
                    ])
                ], 400);
            }

            $systemFee = calculate_deposit_system_fee(
                $request->get('amount'),
                $wallet->coin->deposit_fee,
                $wallet->coin->deposit_fee_type
            );

            $params = [
                'user_id' => Auth::id(),
                'wallet_id' => $wallet->id,
                'symbol' => $wallet->symbol,
                'bank_account_id' => $request->get('bank_account_id'),
                'amount' => $request->get('amount'),
                'system_fee' => $systemFee,
                'api' => $request->get('api'),
                'status' => STATUS_PENDING,
            ];

            if ($deposit = WalletDeposit::create($params)) {
                $response = [
                    RESPONSE_STATUS_KEY => true,
                    RESPONSE_MESSAGE_KEY => __("Deposit has been created successfully.")
                ];

                if (
                    $request->get('api') == API_BANK &&
                    is_array($wallet->coin->api['selected_banks']) &&
                    !empty($wallet->coin->api['selected_banks'])
                ) {
                    $selectedBanks = BankAccount::whereIn('id', $wallet->coin->api['selected_banks'])
                        ->with('country')
                        ->get();

                    $selectedSystemBanks = [];

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
                            "isActive" => active_status($selectedBank->is_active),
                        ];
                    }

                    $response[RESPONSE_DATA] = [
                        'depositDetails' => [
                            'id' => $deposit->id,
                            'user' => auth()->user()->profile->full_name,
                            'wallet' => $deposit->coin->name . ' (' . $deposit->coin->symbol . ')',
                            'amount' => $deposit->amount,
                            'bank' => $deposit->bankAccount->bank_name,
                            'txnId' => $deposit->txn_id,
                            'status' => $deposit->status,
                        ],
                        'userBankDetail' => [
                            'id' => $deposit->bankAccount->id,
                            'bankName' => $deposit->bankAccount->bank_name,
                            'bankAddress' => $deposit->bankAccount->bank_address,
                            'accountHolder' => $deposit->bankAccount->account_holder,
                            'referenceNumber' => $deposit->bankAccount->reference_number,
                            'swift' => $deposit->bankAccount->swift,
                            'iban' => $deposit->bankAccount->iban,
                            'country' => $deposit->bankAccount->country->name,
                            'isActive' => active_status($deposit->bankAccount->is_active),
                            'isVerified' => verified_status($deposit->bankAccount->is_verified),
                        ],
                        'depositWithBanks' => $selectedSystemBanks
                    ];
                }

                return response()->json($response, 200);
            }
        }

        return response()->json([
            RESPONSE_STATUS_KEY => false,
            RESPONSE_MESSAGE_KEY => __("Invalid fiat deposit request.")
        ], 400);
    }

    public function uploadReceipt(BankReceiptUploadRequest $request, Wallet $wallet, WalletDeposit $deposit)
    {
        $wallet->load('coin');
        $systemBank = $request->get('system_bank_id');
        $systemSupportedBanks = $wallet->coin->api['selected_banks'] ?? [];

        if (!in_array($systemBank, $systemSupportedBanks)) {
            return response()->json([
                RESPONSE_STATUS_KEY => false,
                RESPONSE_MESSAGE_KEY => __("Invalid system bank.")
            ], 400);
        }

        if ($request->hasFile('receipt')) {
            $filePath = config('commonconfig.path_deposit_receipt');
            $receipt = app(FileUploadService::class)->upload($request->file('receipt'), $filePath, $deposit->id);
        }

        $params = ['system_bank_account_id' => $systemBank, 'receipt' => $receipt, 'status' => STATUS_REVIEWING];

        if ($deposit->update($params)) {
            return response()->json([
                RESPONSE_STATUS_KEY => true,
                RESPONSE_MESSAGE_KEY => __('Receipt has been uploaded successfully.')
            ], 200);
        }

        return response()->json([
            RESPONSE_STATUS_KEY => false,
            RESPONSE_MESSAGE_KEY => __("Failed to upload receipt.")
        ], 400);
    }

    public function getDepositAddress(Wallet $wallet)
    {
        if ($wallet->coin->type != COIN_TYPE_CRYPTO) {
            return response()->json([
                RESPONSE_STATUS_KEY => false,
                RESPONSE_MESSAGE_KEY => __("Invalid deposit request for cryptocurrency.")
            ], 400);
        }

        if ($wallet->coin->deposit_status != ACTIVE) {
            return response()->json([
                RESPONSE_STATUS_KEY => false,
                RESPONSE_MESSAGE_KEY => __('Deposit is currently disabled.')
            ], 400);
        }

        if ($wallet->address) {
            $walletAddress = $wallet->address;
        } else {
            $api = $wallet->coin->getAssociatedApi();

            if (is_null($api)) {
                return response()->json([
                    RESPONSE_STATUS_KEY => false,
                    RESPONSE_MESSAGE_KEY => __('Network Error! Unable to generate address.')
                ], 400);
            }

            $response = $api->generateAddress();

            if ($response['error'] === 'ok') {
                $wallet->update(['address' => $response['result']['address']]);
                $walletAddress = $response['result']['address'];
            } else {
                return response()->json([
                    RESPONSE_STATUS_KEY => false,
                    RESPONSE_MESSAGE_KEY => __('Network Error! Unable to generate address.')
                ], 400);
            }
        }

        $addressSvg = app(GenerateWalletAddressImage::class)->generateSvg($walletAddress);

        return response()->json([
            RESPONSE_STATUS_KEY => true,
            RESPONSE_DATA => [
                'walletAddress' => $walletAddress,
                'qrCode' => $addressSvg
            ],
        ], 200);
    }
}
