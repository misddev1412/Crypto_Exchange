<?php

namespace App\Http\Controllers\Referral;

use App\Http\Controllers\Controller;
use App\Models\Core\User;
use App\Models\Referral\ReferralEarning;
use App\Services\Core\DataTableService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReferralEarningController extends Controller
{
    public function index()
    {
        $searchFields = [
            ['coin', __('Coin')],
            ['email', __('Email'), 'referralUsers'],
            ['username', __('Username'), 'referralUsers'],
        ];

        $orderFields = [
            ['coin', __('Coin')],
            ['amount', __('Amount')],
            ['email', __('Email'), 'referralUsers'],
            ['username', __('Username'), 'referralUsers'],
        ];

        $downloadableHeadings = [
            ['symbol', __('Wallet')],
            ['amount', __('Amount')],
            ['last_earning_at', __('Last Earning At')],
        ];

        $data['title'] = __('Referral Earning History');

        $queryBuilder = ReferralEarning::select('symbol', DB::raw("sum(amount) as amount"))
            ->addSelect(['last_earning_at' => ReferralEarning::from('referral_earnings as r')
                ->select('created_at')
                ->where('r.referrer_user_id', Auth::id())
                ->whereColumn('r.symbol', 'referral_earnings.symbol')
                ->latest('r.created_at')
                ->limit(1)
            ])
            ->where('referrer_user_id', Auth::id())
            ->with('coin', 'referralUser')
            ->groupBy(["symbol"])
            ->orderBy('amount', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->downloadable($downloadableHeadings)
            ->withoutDateFilter()
            ->create($queryBuilder);

        return view('referral.index', $data);
    }

    public function show(User $user)
    {
        $searchFields = [
            ['coin', __('Coin')],
        ];

        $orderFields = [
            ['coin', __('Coin')],
            ['amount', __('Amount')],
            ['date', __('Date')],
        ];

        $data['user'] = $user;
        $data['title'] = __('Referral Earning History: :name', ['name' => $user->profile->full_name]);

        $queryBuilder = ReferralEarning::select('symbol', DB::raw("sum(amount) as amount"))
            ->where('referral_user_id', $user->id)
            ->where('referrer_user_id', Auth::id())
            ->with('coin')
            ->groupBy("symbol")
            ->orderBy('amount', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->withoutDateFilter()
            ->create($queryBuilder);

        return view('referral.user.show', $data);
    }
}
