<?php

namespace App\Http\Controllers\Withdrawal;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal\WalletWithdrawal;
use App\Services\Core\DataTableService;
use App\Services\Withdrawal\WithdrawalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminWithdrawalHistoryController extends Controller
{
    public function index()
    {
        $searchFields = [
            ['id', __('Reference ID')],
            ['amount', __('Amount')],
            ['address', __('Address')],
            ['txn_id', __('Transaction ID')],
            ['bank_name', __('Bank'), 'bankAccount'],
            ['symbol', __('Wallet')],
            ['email', __('Email'), 'user']
        ];

        $orderFields = [
            ['created_at', __('Date')],
            ['symbol', __('Wallet')],
            ['amount', __('Amount')],
        ];

        $filterFields = [
            ['status', __('Status'), transaction_status()],
            ['api', __('Payment Method'), coin_apis()],
        ];

        $downloadableHeadings = [
            ['created_at', __('Date')],
            ['id', __('Reference ID')],
            ['email', __('Email'), 'user'],
            ['address', __('Address')],
            ['bank_name', __('Bank'), 'bankAccount'],
            ['amount', __('Amount')],
            ['system_fee', __('Fee')],
            ['txn_id', __('Transaction ID')],
            ['status', __('Status')],
        ];

        $queryBuilder = WalletWithdrawal::with('user', 'bankAccount')
            ->orderBy('created_at', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->setFilterFields($filterFields)
            ->downloadable($downloadableHeadings)
            ->create($queryBuilder);

        $data['title'] = __('Withdrawal History');
        return view('withdrawal.admin.history.index', $data);
    }

    public function show(WalletWithdrawal $withdrawal): View
    {
        return app(WithdrawalService::class, [$withdrawal])->show();
    }

    public function destroy(WalletWithdrawal $withdrawal): RedirectResponse
    {
        return app(WithdrawalService::class, [$withdrawal])->destroy();
    }

    public function update(WalletWithdrawal $withdrawal): RedirectResponse
    {
        $response = app(WithdrawalService::class, [$withdrawal])->approve();

        if ($response[RESPONSE_STATUS_KEY]) {
            return redirect()
                ->route('admin.history.withdrawals.show', $withdrawal->id)
                ->with(RESPONSE_TYPE_SUCCESS, $response[RESPONSE_MESSAGE_KEY]);
        }
        return redirect()
            ->back()
            ->with(RESPONSE_TYPE_ERROR, $response[RESPONSE_MESSAGE_KEY]);
    }
}
