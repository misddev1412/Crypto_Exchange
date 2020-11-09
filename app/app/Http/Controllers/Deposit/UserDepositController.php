<?php

namespace App\Http\Controllers\Deposit;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deposit\BankReceiptUploadRequest;
use App\Http\Requests\Deposit\UserDepositRequest;
use App\Models\BankAccount\BankAccount;
use App\Models\Deposit\WalletDeposit;
use App\Models\Wallet\Wallet;
use App\Services\Core\DataTableService;
use App\Services\Core\FileUploadService;
use App\Services\Wallet\GenerateWalletAddressImage;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserDepositController extends Controller
{
    public function index(Wallet $wallet): View
    {
        $searchFields = [
            ['id', __('Reference ID')],
            ['amount', __('Amount')],
            ['address', __('Address')],
            ['bank_name', __('Bank'), 'bankAccount'],
            ['txn_id', __('Transaction ID')],
            ['symbol', __('Wallet')],
        ];

        $orderFields = [
            ['amount', __('Amount')],
            ['address', __('Address')],
            ['symbol', __('Wallet')],
            ['created_at', __('Date')],
        ];

        $filterFields = [
            ['status', __('Status'), transaction_status()],
        ];

        $queryBuilder = $wallet->deposits()
            ->with("bankAccount")
            ->orderBy('created_at', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->setFilterFields($filterFields)
            ->create($queryBuilder);

        $data['title'] = __("Deposit History");
        return view('deposit.user.index', $data);
    }

    public function create(Wallet $wallet)
    {
        $data['wallet'] = $wallet;
        $data['title'] = __('Deposit :coin', ['coin' => $wallet->symbol]);

        if ($wallet->coin->type === COIN_TYPE_CRYPTO) {
            $data['walletAddress'] = __('Deposit is currently disabled.');
            if ($wallet->coin->deposit_status == ACTIVE) {
                $api = $wallet->coin->getAssociatedApi();
                if ($wallet->address) {
                    $data['walletAddress'] = $wallet->address;
                } else {
                    if (is_null($api)) {
                        $data['walletAddress'] = __('Unable to generate address.');
                    } else {
                        $response = $api->generateAddress();
                        if ($response['error'] === 'ok') {
                            $wallet->update(['address' => $response['result']['address']]);
                            $data['walletAddress'] = $response['result']['address'];
                        } else {
                            $data['walletAddress'] = $response['error'];
                        }
                    }
                }
                $data['addressSvg'] = app(GenerateWalletAddressImage::class)->generateSvg($data['walletAddress']);
            }
            return view('deposit.user.wallet_address', $data);
        } else if ($wallet->coin->type == COIN_TYPE_FIAT) {
            $data['apis'] = Arr::only(fiat_apis(), $wallet->coin->api['selected_apis'] ?? []);
            $data['bankAccounts'] = BankAccount::where('user_id', Auth::id())
                ->where('is_active', ACTIVE)
                ->pluck('bank_name', 'id');
            return view('deposit.user.deposit_form', $data);
        }

        return view('errors.404', $data);
    }

    public function store(UserDepositRequest $request, Wallet $wallet)
    {
        if ($request->get('api') === API_BANK) {

            if (bccomp($wallet->coin->minimum_deposit_amount, $request->amount) > 0) {
                return redirect()
                    ->back()
                    ->with(RESPONSE_TYPE_ERROR, __("The deposit amount must be greater than :amount.", [
                        'amount' => $wallet->coin->minimum_deposit_amount
                    ]))
                    ->withInput();
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
                return redirect()
                    ->route('user.wallets.deposits.show', ['wallet' => $wallet->symbol, 'deposit' => $deposit->id])
                    ->with(RESPONSE_TYPE_SUCCESS, __("Deposit has been created successfully."))
                    ->withInput();
            }
        }

        return redirect()
            ->back()
            ->with(RESPONSE_TYPE_ERROR, __("Invalid request."));
    }

    public function show(Wallet $wallet, WalletDeposit $deposit)
    {
        $wallet->load('coin');
        $deposit->load('user.profile', 'bankAccount.country');
        if ($deposit->status === STATUS_PENDING && $deposit->api === API_BANK) {
            $systemBankIds = $wallet->coin->api['selected_banks'] ?? [];
            $data['systemBanks'] = BankAccount::whereIn('id', $systemBankIds)->where('is_active', ACTIVE)->with('country')->get();
        }
        $data['wallet'] = $wallet;
        $data['deposit'] = $deposit;
        $data['title'] = __("Deposit Details");
        return view('deposit.user.show', $data);
    }

    public function update(BankReceiptUploadRequest $request, Wallet $wallet, WalletDeposit $deposit)
    {
        $wallet->load('coin');
        $systemBank = $request->get('system_bank_id');
        $systemSupportedBanks = $wallet->coin->api['selected_banks'] ?? [];
        if (!in_array($systemBank, $systemSupportedBanks)) {
            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __("Invalid system bank."));
        }

        if ($request->hasFile('receipt')) {
            $filePath = config('commonconfig.path_deposit_receipt');
            $receipt = app(FileUploadService::class)->upload($request->file('receipt'), $filePath, $deposit->id);
        }

        $params = ['system_bank_account_id' => $systemBank, 'receipt' => $receipt, 'status' => STATUS_REVIEWING];

        if ($deposit->update($params)) {
            return redirect()->route('user.wallets.deposits.show', ['wallet' => $wallet->symbol, 'deposit' => $deposit->id])->with(RESPONSE_TYPE_SUCCESS, __('Receipt has been uploaded successfully.'));
        }
        return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __("Failed to upload receipt."));
    }
}
