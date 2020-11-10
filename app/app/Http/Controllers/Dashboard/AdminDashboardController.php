<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Coin\Coin;
use App\Models\Coin\CoinPair;
use App\Models\Core\User;
use App\Models\Deposit\WalletDeposit;
use App\Models\Exchange\Exchange;
use App\Models\Kyc\KycVerification;
use App\Models\Post\Post;
use App\Models\Post\PostComment;
use App\Models\Ticket\Ticket;
use App\Models\Withdrawal\WalletWithdrawal;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $data['title'] = __('Admin Dashboard');
        return view('dashboard.admin.show', $data);
    }

    public function getFeaturedCoins(): JsonResponse
    {

        $status = RESPONSE_TYPE_ERROR;
        $data = [];

        for ($i = 1; $i <= 4; $i++) {
            $featuredCoin = Coin::where('symbol', settings('dashboard_coin_' . $i))->first();

            if(!empty($featuredCoin)) {
               $status = RESPONSE_TYPE_SUCCESS;
               $data['dashboardCoins']['coin_' . $i]['name'] = $featuredCoin->name;
               $data['dashboardCoins']['coin_' . $i]['symbol'] = $featuredCoin->symbol;
               $data['dashboardCoins']['coin_' . $i]['revenue_cart_url'] = route('coins.revenue-graph', $featuredCoin->symbol);
               $data['dashboardCoins']['coin_' . $i]['icon'] = get_coin_icon($featuredCoin->icon);
               $data['dashboardCoins']['coin_' . $i]['primary_balance'] = $featuredCoin->systemWallet->primary_balance;
           }
        }
        return response()->json([RESPONSE_STATUS_KEY => $status, RESPONSE_DATA => $data]);
    }

    public function getUserReports(): JsonResponse
    {
        $users = User::all();
        $data['totalUsers'] = $users->count();
        $data['totalActiveUsers'] = $users->where('status', STATUS_ACTIVE)->count();
        $data['totalSuspendedUsers'] = $users->where('status', STATUS_INACTIVE)->count();
        $data['totalVerifiedUsers'] = $users->where('is_email_verified', VERIFIED)->count();
        return response()->json(['userReports' => $data]);
    }

    public function getTicketReports(): JsonResponse
    {
        $tickets = Ticket::all();
        $data['totalTicket'] = $tickets->count();
        $data['totalOpenTicket'] = $tickets->where('status', STATUS_OPEN)->count();
        $data['totalClosedTicket'] = $tickets->where('status', STATUS_CLOSED)->count();
        $data['totalResolvedTicket'] = $tickets->where('status', STATUS_RESOLVED)->count();
        return response()->json(['ticketReports' => $data]);
    }

    public function getRecentRegisterUsers(): JsonResponse
    {
        $users = User::with(["profile"])
            ->orderBy('created_at', 'desc')->take(5)->get();
        $view = view('dashboard.admin._user_list_template', ['users' => $users])->render();
        return response()->json(['view' => $view]);
    }

    public function getCoinPairTrade(): JsonResponse
    {
        $startDate = Carbon::now()->startOfWeek();
        $endDate = Carbon::now()->endOfWeek();
        $coinPair = CoinPair::where('name', settings('dashboard_coin_pair'))->first();
        if (!is_null($coinPair)) {
            $orderType = ORDER_TYPE_BUY;
            $exchanges = $coinPair->exchanges()
                ->select(
                    DB::raw("DAYNAME(created_at) as day"),
                    DB::raw("sum(total) as total"),
                    DB::raw("sum(IF(order_type = '{$orderType}', price*fee, fee)) as revenue"),
                )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('day')
                ->get()
                ->groupBy('day')
                ->toArray();

            $tradeGraph['total'] = 0;
            $tradeGraph['revenue'] = 0;


            while ($startDate <= $endDate) {
                $tradeGraph['days'][] = $startDate->dayName;
                $tradeGraph['revenues'][] = isset($exchanges[$startDate->dayName]) ? $exchanges[$startDate->dayName][0]['revenue'] : 0;
                $tradeGraph['total'] = bcadd(
                    $tradeGraph['total'],
                    isset($exchanges[$startDate->dayName]) ? $exchanges[$startDate->dayName][0]['total'] : 0
                );
                $tradeGraph['revenue'] = bcadd(
                    $tradeGraph['revenue'],
                    isset($exchanges[$startDate->dayName]) ? $exchanges[$startDate->dayName][0]['revenue'] : 0
                );
                $startDate->addDay();
            }
            $tradeGraph['coinPairName'] = $coinPair->trade_pair;
        } else {
            $tradeGraph = [];
        }
        return response()->json(['coinPairTrade' => $tradeGraph]);
    }

    public function getRecentWithdrawals(): JsonResponse
    {
        $recentWithdrawals = WalletWithdrawal::where('status', STATUS_COMPLETED)->orderBy('created_at', 'desc')->take(4)->get();
        $view = view('dashboard.admin._withdrawal_list_template', ['recentWithdrawals' => $recentWithdrawals])->render();
        return response()->json(['view' => $view]);
    }

    public function getRecentDeposits(): JsonResponse
    {
        $recentDeposits = WalletDeposit::where('status', STATUS_COMPLETED)->orderBy('created_at', 'desc')->take(4)->get();
        $view = view('dashboard.admin._deposit_list_template', ['recentDeposits' => $recentDeposits])->render();
        return response()->json(['view' => $view]);
    }

    public function getRecentTrades(): JsonResponse
    {
        $recentTrades = Exchange::orderBy('created_at', 'desc')->take(4)->get();
        $view = view('dashboard.admin._trade_list_template', ['recentTrades' => $recentTrades])->render();
        return response()->json(['view' => $view]);
    }

    public function getOtherReports(): JsonResponse
    {
        // coins
        $coins = Coin::all();
        $data['totalCoin'] = $coins->count();
        $data['totalActiveCoin'] = $coins->where('is_active', ACTIVE)->count();

        // coin pair
        $coinPairs = CoinPair::all();
        $data['totalCoinPair'] = $coinPairs->count();
        $data['totalActiveCoinPair'] = $coinPairs->where('is_active', ACTIVE)->count();

        // kyc
        $data['totalPendingReviewKyc'] = KycVerification::where('status', STATUS_REVIEWING)->count();

        // withdrawal
        $data['totalPendingWithdrawal'] = WalletWithdrawal::where('status', STATUS_REVIEWING)->count();

        // deposit
        $data['totalPendingDeposit'] = WalletDeposit::where('status', STATUS_REVIEWING)->count();

        // post and comment
        $data['totalPost'] = Post::where('is_published', ACTIVE)->count();
        $data['totalComment'] = PostComment::count();

        return response()->json(['reports' => $data]);
    }
}
