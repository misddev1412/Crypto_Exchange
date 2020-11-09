<?php

namespace App\Http\Controllers\Deposit;

use App\Http\Controllers\Controller;
use App\Jobs\Deposit\DepositProcessJob;
use App\Models\Deposit\WalletDeposit;
use App\Services\Core\DataTableService;
use App\Services\Deposit\DepositService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminDepositHistoryController extends Controller
{
    public function index()
    {
        $data['title'] = __('Deposit History');

        $data['userId'] = Auth::id();

        $searchFields = [
            ['id', __('Reference ID')],
            ['email', __('Email'), 'user'],
            ['symbol', __('Wallet')],
            ['amount', __('Amount')],
            ['txn_id', __('Txn ID')],
        ];

        $orderFields = [
            ['symbol', __('Wallet')],
            ['amount', __('Amount')],
            ['created_at', __('Date')],
        ];

        $filterFields = [
            ['status', __("Status"), transaction_status()],
            ['api', __("Payment Method"), coin_apis()]
        ];

        $downloadableHeadings = [
            ['created_at', __("Date")],
            ['id', __("Reference ID")],
            ['email', __("Email"), 'user'],
            ['address', __("Address")],
            ['bank_name', __("Bank"), 'bankAccount'],
            ['symbol', __("Wallet")],
            ['amount', __("Amount")],
            ['system_fee', __("Fee")],
            ['status', __("Status")],
        ];


        $queryBuilder = WalletDeposit::with("user")
            ->orderBy('created_at');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->setFilterFields($filterFields)
            ->downloadable($downloadableHeadings)
            ->create($queryBuilder);

        return view('deposit.admin.history.index', $data);
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
