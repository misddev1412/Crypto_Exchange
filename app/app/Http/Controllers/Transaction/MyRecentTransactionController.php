<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Coin\Exchange\Exchange;
use App\Models\Deposit\WalletDeposit;
use App\Models\Withdrawal\WalletWithdrawal;
use App\Services\Core\DataTableService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MyRecentTransactionController extends Controller
{
    public function index()
    {
        $exchange = Exchange::where('user_id', Auth::id())
            ->select([
                'id',
                'total as amount',
                'coin',
                'type',
                'created_at'
            ]);

        $withdrawals = WalletWithdrawal::where('user_id', Auth::id())
            ->where('status', STATUS_COMPLETED)
            ->select(
                'id',
                'amount',
                'symbol as coin',
                DB::raw("'withdrawal' as type"),
                'created_at'
            );

        $transactions = WalletDeposit::where('user_id', Auth::id())
            ->where('status', STATUS_COMPLETED)
            ->select(
                'id',
                'amount',
                'symbol as coin',
                DB::raw("'deposit' as type"),
                'created_at'
            );

        $searchFields = [
            ['symbol', __('Wallet')],
            ['amount', __('Amount')],
        ];

        $orderFields = [
            ['coin', __('Wallet')],
            ['amount', __('Amount')],
            ['created_at', __('Date')],
        ];

        $filterFields = [
            ['type', __('Type'), ['buy', 'sell', 'deposit', 'withdrawal']],
        ];

        $data['title'] = __('Recent Transactions');
        $queryBuilder = $transactions->union($withdrawals)->union($exchange)->orderBy('created_at', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->setFilterFields($filterFields)
            ->create($queryBuilder);

        return view('transactions.my_recent_transactions', $data);
    }
}
