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
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SystemDepositController extends Controller
{
    public function index(Wallet $wallet): View
    {
        $wallet->load('coin');
        $data['title'] = __('Deposit History');

        $data['userId'] = Auth::id();
        $searchFields = [
            ['name', __('Name')],
            ['id', __('Reference ID')],
            ['amount', __('Amount')],
            ['address', __('Address')],
            ['txn_id', __('Transaction ID')],
            ['symbol', __('Wallet')],
        ];

        $orderFields = [
            ['name', __('Name')],
            ['created_at', __('Date')],
        ];

        $filterFields = [
            ['wallet_deposits.status', __('Category'), transaction_status()],
        ];

        $queryBuilder = $wallet->deposits()
            ->orderBy('created_at', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->setFilterFields($filterFields)
            ->create($queryBuilder);
        return view('deposit.admin.index', $data);
    }

    public function create(Wallet $wallet)
    {
        $data['wallet'] = $wallet;
        $wallet->load('coin');
        $data['title'] = __('Deposit :coin', ['coin' => $wallet->symbol]);

        if ($wallet->coin->type === COIN_TYPE_CRYPTO) {
            $data['walletAddress'] = __('Deposit is currently disabled.');
            if ($data['wallet']->coin->deposit_status == ACTIVE) {
                $wallet->getService();
                if (is_null($wallet->service)) {
                    $data['walletAddress'] = __('Unable to generate address.');
                } else {
                    $response = $wallet->service->generateAddress();
                    if ($response['error'] === 'ok') {
                        $data['walletAddress'] = $response['result']['address'];
                        $data['addressSvg'] = app(GenerateWalletAddressImage::class)->generateSvg($data['walletAddress']);
                    } else {
                        $data['walletAddress'] = $response['error'];
                    }
                }
            }
            return view('deposit.user.wallet_address', $data);
        }
        else if ($data['wallet']->coin->type == COIN_TYPE_FIAT) {
            $data['apis'] = Arr::only(fiat_apis(), $wallet->coin->api['selected_apis'] ?? []);
            $data['bankAccounts'] = BankAccount::where('user_id', Auth::id())
                ->where('is_verified', VERIFIED)
                ->where('is_active', ACTIVE)
                ->pluck('bank_name', 'id');
            return view('deposit.admin.deposit_form', $data);
        }

        return view('errors.404', $data);
    }

    public function store(UserDepositRequest $request, Wallet $wallet)
    {
        $wallet->load('coin');
        if ($request->get('api') === API_BANK) {
            $params = [
                'user_id' => Auth::id(),
                'symbol' => $wallet->symbol,
                'wallet_id' => $wallet->id,
                'bank_account_id' => $request->get('bank_account_id'),
                'amount' => $request->get('amount'),
                'api' => $request->get('api'),
                'status' => STATUS_COMPLETED,
            ];

            DB::beginTransaction();
            $wallet->increment('primary_balance', $request->get('amount'));
            $deposit = WalletDeposit::create($params);

            if (!$deposit) {
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(RESPONSE_TYPE_ERROR, __("Invalid request."));
            }
            DB::commit();
            return redirect()
                ->route('admin.system-wallets.deposit.show', ['wallet' => $wallet->symbol, 'deposit' => $deposit->id])
                ->with(RESPONSE_TYPE_SUCCESS, __("Deposit has been created successfully."));
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
