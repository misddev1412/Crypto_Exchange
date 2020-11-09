<?php

namespace App\Http\Controllers\Deposit;

use App\Http\Controllers\Controller;
use App\Models\Deposit\WalletDeposit;
use App\Services\Core\DataTableService;
use App\Services\Deposit\DepositService;
use Illuminate\Support\Facades\Auth;

class AdminBankDepositReviewController extends Controller
{
    public function index()
    {
        $data['title'] = __('Review Bank Deposits');

        $data['userId'] = Auth::id();
        $searchFields = [
            ['id', __('Reference ID')],
            ['email', __('Email'), 'user'],
            ['bank_name', __('Bank'), 'bankAccount'],
            ['symbol', __('Wallet')],
            ['amount', __('Amount')],
        ];

        $orderFields = [
            ['symbol', __('Wallet')],
            ['amount', __('Amount')],
            ['created_at', __('Date')],
        ];

        $queryBuilder = WalletDeposit::with("user", "bankAccount")
            ->where('api', API_BANK)
            ->where('status', STATUS_REVIEWING)
            ->orderBy('created_at');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->create($queryBuilder);

        return view('deposit.admin.review_bank_deposits.index', $data);
    }

    public function show(WalletDeposit $deposit)
    {
        return app(DepositService::class)->show($deposit);
    }

    public function update(WalletDeposit $deposit)
    {
        return app(DepositService::class)->approve($deposit);
    }

    public function destroy(WalletDeposit $deposit)
    {
        return app(DepositService::class)->cancel($deposit);
    }
}
