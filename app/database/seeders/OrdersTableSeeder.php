<?php

namespace Database\Seeders;

use App\Models\Core\User;
use App\Models\Exchange\Exchange;
use App\Models\Order\Order;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('exchanges')->truncate();
        DB::table('orders')->truncate();
        Schema::enableForeignKeyConstraints();

        $faker = Factory::create();
        $startDate = Carbon::now()->subDays(30)->startOfDay();
        $endDate = Carbon::now();

        $orders = [];
        $exchanges = [];
        $orderSlice = random_int(100, 200);
        $trend = $faker->boolean;
        $currentPrice = 9640;

        while ($startDate <= $endDate) {
            $orderType = $faker->randomElement([ORDER_TYPE_BUY, ORDER_TYPE_SELL]);
            $min = $max = $currentPrice;
            if ($trend) {
                $max = bcadd($currentPrice, 5);
            } else {
                $min = bcsub($currentPrice, 5);
            }
            $price = $currentPrice = $faker->randomFloat(8, $min, $max);
            $amount = $faker->randomFloat(8, 0.003, 0.99);

            $intervalDate = Carbon::parse(intval($startDate->unix() / 300) * 300);
            if ($startDate->equalTo($intervalDate) && count($orders) > 0) {
                $price = end($orders)['price'];
            }

            $orders[] = $order1 = [
                'id' => Str::uuid()->toString(),
                'user_id' => User::inRandomOrder()->first()->id,
                'trade_coin' => 'BTC',
                'base_coin' => 'USD',
                'trade_pair' => 'BTC_USD',
                'category' => ORDER_CATEGORY_LIMIT,
                'type' => $orderType,
                'price' => $price,
                'amount' => $amount,
                'total' => bcmul($price, $amount),
                'maker_fee_in_percent' => settings('exchange_maker_fee'),
                'taker_fee_in_percent' => settings('exchange_taker_fee'),
                'status' => STATUS_COMPLETED,
                'created_at' => $startDate->toDateTimeString(),
                'updated_at' => $startDate->toDateTimeString(),
            ];

            $orders[] = $order2 = [
                'id' => Str::uuid()->toString(),
                'user_id' => User::inRandomOrder()->first()->id,
                'trade_coin' => 'BTC',
                'base_coin' => 'USD',
                'trade_pair' => 'BTC_USD',
                'category' => ORDER_CATEGORY_LIMIT,
                'type' => $orderType == ORDER_TYPE_BUY ? ORDER_TYPE_SELL : ORDER_TYPE_BUY,
                'price' => $price,
                'amount' => $amount,
                'total' => bcmul($price, $amount),
                'maker_fee_in_percent' => settings('exchange_maker_fee'),
                'taker_fee_in_percent' => settings('exchange_taker_fee'),
                'status' => STATUS_COMPLETED,
                'created_at' => $startDate->toDateTimeString(),
                'updated_at' => $startDate->toDateTimeString(),
            ];

            $exchanges[] = [
                'id' => Str::uuid()->toString(),
                'user_id' => $order1['user_id'],
                'order_id' => $order1['id'],
                'trade_coin' => $order1['trade_coin'],
                'base_coin' => $order1['base_coin'],
                'trade_pair' => $order1['trade_pair'],
                'amount' => $order1['amount'],
                'price' => $order1['price'],
                'total' => $order1['total'],
                'fee' => $this->calculateTradeFee($order1, $order1['amount'], $order1['total'], true),
                'order_type' => $order1['type'],
                'related_order_id' => $order2['id'],
                'is_maker' => true,
                'created_at' => $startDate->toDateTimeString(),
                'updated_at' => $startDate->toDateTimeString(),
            ];

            $exchanges[] = [
                'id' => Str::uuid()->toString(),
                'user_id' => $order2['user_id'],
                'order_id' => $order2['id'],
                'trade_coin' => $order2['trade_coin'],
                'base_coin' => $order2['base_coin'],
                'trade_pair' => $order2['trade_pair'],
                'amount' => $order2['amount'],
                'price' => $order2['price'],
                'total' => $order2['total'],
                'fee' => $this->calculateTradeFee($order2, $order2['amount'], $order2['total'], false),
                'order_type' => $order2['type'],
                'related_order_id' => $order1['id'],
                'is_maker' => false,
                'created_at' => $startDate->toDateTimeString(),
                'updated_at' => $startDate->toDateTimeString(),
            ];

            $orderSlice--;
            if ($orderSlice === 0) {
                $trend = $faker->boolean;
                $orderSlice = random_int(100, 200);
            }

            $startDate->addMinute();
        }

        foreach (array_chunk($orders, 1000) as $chunkOrders) {
            Order::insert($chunkOrders);
        }

        foreach (array_chunk($exchanges, 1000) as $chunkExchanges) {
            Exchange::insert($chunkExchanges);
        }
    }

    private function calculateTradeFee($order, $amount, $total, $isMaker)
    {
        $feePercent = $isMaker ? $order['maker_fee_in_percent'] : $order['taker_fee_in_percent'];
        return bcdiv(bcmul($order['type'] === ORDER_TYPE_BUY ? $amount : $total, $feePercent), '100');
    }
}
