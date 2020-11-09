<?php

namespace App\Http\Controllers\Coin;

use App\Http\Controllers\Controller;
use App\Models\Coin\Coin;
use App\Models\Exchange\Exchange;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCoinRevenueGraphController extends Controller
{
    public function index(Request $request, Coin $coin)
    {
        $data['title'] = __('Revenue Graph Of :coin', ['coin' => $coin->symbol]);

        $data['depositInfo'] = $coin->deposits()
            ->select(DB::raw("sum(amount) as total_deposit"), DB::raw("sum(system_fee) as total_revenue"))
            ->where('status', STATUS_COMPLETED)
            ->first();

        $data['withdrawalInfo'] = $coin->withdrawals()
            ->select(DB::raw("sum(amount) as total_withdrawal"), DB::raw("sum(system_fee) as total_revenue"))
            ->where('status', STATUS_COMPLETED)
            ->first();

        $data['tradeInfo'] = Exchange::select(
            DB::raw("sum(amount) as total_trade"),
            DB::raw("sum(fee) as gross_revenue"))
            ->where(function ($q) use ($coin) {
                $q->where('base_coin', $coin->symbol)
                    ->where('order_type', ORDER_TYPE_SELL);
            })->orWhere(function ($q) use ($coin) {
                $q->where('trade_coin', $coin->symbol)
                    ->where('order_type', ORDER_TYPE_BUY);
            })
            ->first();

        $data['coin'] = $coin;
        return view('coins.admin.revenue-graph', $data);
    }

    public function getDepositRevenueGraphData(Coin $coin): JsonResponse
    {
        $date = Carbon::now();
        $deposits = $coin->deposits()
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%e') as day"),
                DB::raw("sum(amount) as total"),
                DB::raw("sum(system_fee) as revenue")
            )
            ->whereYear('created_at', $date->format('Y'))
            ->whereMonth('created_at', $date->format('m'))
            ->where('status', STATUS_COMPLETED)
            ->groupBy('day')
            ->get()
            ->groupBy('day')
            ->toArray();

        $depositRevenueGraph['total_deposit'] = 0;
        $depositRevenueGraph['total_revenue'] = 0;
        foreach (range(1, intval($date->endOfMonth()->format('d'))) as $day) {
            $depositRevenueGraph['days'][] = $day;
            $depositRevenueGraph['revenues'][] = isset($deposits[$day]) ? $deposits[$day][0]['revenue'] : 0;
            $depositRevenueGraph['total_deposit'] = bcadd($depositRevenueGraph['total_deposit'], isset($deposits[$day]) ?
                $deposits[$day][0]['total'] : 0);
            $depositRevenueGraph['total_revenue'] = bcadd($depositRevenueGraph['total_revenue'], isset($deposits[$day]) ?
                $deposits[$day][0]['revenue'] : 0);
        }
        ksort($depositRevenueGraph);

        return response()->json(['depositRevenueGraph' => $depositRevenueGraph]);
    }

    public function getWithdrawalRevenueGraphData(Coin $coin): JsonResponse
    {
        $date = Carbon::now();
        $withdrawals = $coin->withdrawals()
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%e') as day"),
                DB::raw("sum(amount) as total"),
                DB::raw("sum(system_fee) as revenue")
            )
            ->whereYear('created_at', $date->format('Y'))
            ->whereMonth('created_at', $date->format('m'))
            ->where('status', STATUS_COMPLETED)
            ->groupBy('day')
            ->get()
            ->groupBy('day')
            ->toArray();

        $withdrawalRevenueGraph['total_withdrawal'] = 0;
        $withdrawalRevenueGraph['total_revenue'] = 0;
        foreach (range(1, intval($date->endOfMonth()->format('d'))) as $day) {
            $withdrawalRevenueGraph['days'][] = $day;
            $withdrawalRevenueGraph['revenues'][] = isset($withdrawals[$day]) ? $withdrawals[$day][0]['revenue'] : 0;
            $withdrawalRevenueGraph['total_withdrawal'] = bcadd(
                $withdrawalRevenueGraph['total_withdrawal'],
                isset($withdrawals[$day]) ? $withdrawals[$day][0]['total'] : 0
            );
            $withdrawalRevenueGraph['total_revenue'] = bcadd(
                $withdrawalRevenueGraph['total_revenue'],
                isset($withdrawals[$day]) ? $withdrawals[$day][0]['revenue'] : 0
            );
        }
        ksort($withdrawalRevenueGraph);

        return response()->json(['withdrawalRevenueGraph' => $withdrawalRevenueGraph]);
    }

    public function getTradeRevenueGraphData(Coin $coin): JsonResponse
    {
        $date = Carbon::now();
        $exchanges = Exchange::select(
            DB::raw("DATE_FORMAT(created_at, '%e') as day"),
            DB::raw("sum(amount) as total"),
            DB::raw("sum(fee) as gross_revenue"),
            DB::raw("sum(referral_earning) as referral_expense"))
            ->where(function ($query) use ($coin) {
                $query->where(function ($q) use ($coin) {
                    $q->where('base_coin', $coin->symbol)
                        ->where('order_type', ORDER_TYPE_SELL);
                })->orWhere(function ($q) use ($coin) {
                    $q->where('trade_coin', $coin->symbol)
                        ->where('order_type', ORDER_TYPE_BUY);
                });
            })
            ->whereYear('created_at', $date->format('Y'))
            ->whereMonth('created_at', $date->format('m'))
            ->groupBy('day')
            ->get()
            ->groupBy('day')
            ->toArray();

        $tradeRevenueGraph['total_trade'] = 0;
        $tradeRevenueGraph['total_gross_revenue'] = 0;
        $tradeRevenueGraph['total_net_revenue'] = 0;
        $tradeRevenueGraph['total_referral_expense'] = 0;
        foreach (range(1, intval($date->endOfMonth()->format('d'))) as $day) {
            $totalTrade = isset($exchanges[$day]) ? $exchanges[$day][0]['total'] : 0;
            $grossRevenue = isset($exchanges[$day]) ? $exchanges[$day][0]['gross_revenue'] : 0;
            $referralExpense = isset($exchanges[$day]) ? $exchanges[$day][0]['referral_expense'] : 0;
            $netRevenue = bcsub($grossRevenue, $referralExpense);

            $tradeRevenueGraph['days'][] = $day;
            $tradeRevenueGraph['gross_revenues'][] = $grossRevenue;
            $tradeRevenueGraph['net_revenues'][] = $netRevenue;
            $tradeRevenueGraph['referral_expenses'][] = $referralExpense;

            $tradeRevenueGraph['total_trade'] = bcadd($tradeRevenueGraph['total_trade'], $totalTrade);
            $tradeRevenueGraph['total_gross_revenue'] = bcadd($tradeRevenueGraph['total_gross_revenue'], $grossRevenue);
            $tradeRevenueGraph['total_net_revenue'] = bcadd($tradeRevenueGraph['total_net_revenue'], $netRevenue);
            $tradeRevenueGraph['total_referral_expense'] = bcadd($tradeRevenueGraph['total_referral_expense'], $referralExpense);
        }
        ksort($tradeRevenueGraph);
        return response()->json(['tradeRevenueGraph' => $tradeRevenueGraph]);
    }
}
