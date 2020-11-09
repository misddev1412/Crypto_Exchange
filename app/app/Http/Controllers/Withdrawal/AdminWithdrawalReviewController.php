<?php

namespace App\Http\Controllers\Withdrawal;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal\WalletWithdrawal;
use App\Services\Core\DataTableService;
use App\Services\Withdrawal\WithdrawalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminWithdrawalReviewController extends Controller
{
    public function index(): View
    {
        $searchFields = [
            ['id', __('Reference ID')],
            ['address', __('Address')],
            ['symbol', __('Wallet')],
            ['email', __('Email'), 'users'],
            ['bank_name', __('Bank'), 'bankAccount'],
        ];

        $orderFields = [
            ['created_at', __('Date')],
            ['symbol', __('Wallet')]
        ];

        $filtersFields = [
            ['api', __('Payment Method'), coin_apis()]
        ];

        $queryBuilder = WalletWithdrawal::with('user', 'bankAccount')
            ->where('status', STATUS_REVIEWING)
            ->orderBy('created_at');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setFilterFields($filtersFields)
            ->setOrderFields($orderFields)
            ->create($queryBuilder);

        $data['title'] = __('Review Withdrawals');

        return view('withdrawal.admin.review.index', $data);
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
                ->route('admin.review.withdrawals.show', $withdrawal->id)
                ->with(RESPONSE_TYPE_SUCCESS, $response[RESPONSE_MESSAGE_KEY]);
        }
        return redirect()
            ->back()
            ->with(RESPONSE_TYPE_ERROR, $response[RESPONSE_MESSAGE_KEY]);
    }
}
