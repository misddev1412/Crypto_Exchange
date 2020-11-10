<?php


namespace App\Services\Orders;

use App\Broadcasts\Exchange\OrderBroadcast;
use App\Jobs\Order\ProcessOrderJob;
use App\Models\Coin\CoinPair;
use App\Models\Core\User;
use App\Models\Order\Order;
use App\Models\Wallet\Wallet;
use App\Services\Logger\Logger;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use function auth;

class CreateOrderService
{
    public Request $request;
    public CoinPair $tradePair;
    public User $user;
    public Order $order;
    public Wallet $wallet;
    public float $decreaseBalance;

    public function __construct()
    {
        $this->user = auth()->user();
        $this->request = request();
    }

    public function create()
    {
        $this->setTradePair($this->request->trade_pair);
        $this->setWallet();
        $settingTolerance = (string)settings('trading_price_tolerance');

        // checking tolerance if the order category is limit.
        if (
            $this->request->category !== ORDER_CATEGORY_MARKET &&
            bccomp($settingTolerance, '0', 2) > 0
        ) {
            $lastPrice = (string)($this->request->category === ORDER_CATEGORY_LIMIT ? $this->tradePair->last_price : $this->request->stop);
            $tolerancePrice = bcdiv(bcmul($lastPrice, $settingTolerance), "100");
            $highTolerance = bcadd($lastPrice, $tolerancePrice);
            $lowTolerance = bcsub($lastPrice, $tolerancePrice);

            if (bccomp($this->request->price, $highTolerance) > 0 || bccomp($this->request->price, $lowTolerance) < 0) {
                return [
                    RESPONSE_STATUS_KEY => false,
                    RESPONSE_MESSAGE_KEY => __("The price must be between :lowTolerance and :highTolerance", ['lowTolerance' => $lowTolerance, 'highTolerance' => $highTolerance])
                ];
            }
        }

        if ($this->wallet->is_system_wallet) {
            return [
                RESPONSE_STATUS_KEY => false,
                RESPONSE_MESSAGE_KEY => __("Failed to place order.")
            ];
        }

        if (settings('enable_kyc_verification_in_exchange') && $this->user->is_id_verified != VERIFIED) {
            return [
                RESPONSE_STATUS_KEY => false,
                RESPONSE_MESSAGE_KEY => __("Your account must be KYC verified to place an order.")
            ];
        }

        //If the order is not market and order type is not sell then
        if (!($this->request->get('category') === ORDER_CATEGORY_MARKET && $this->request->get('order_type') === ORDER_TYPE_SELL)) {
            $total = $this->request->input('total');

            $incomingCoinType = $this->request->order_type === ORDER_TYPE_BUY ? $this->tradePair->coin->type : $this->tradePair->baseCoin->type;
            $minimumTotal = get_minimum_total($incomingCoinType);

            if (bccomp($minimumTotal, $total) == 1) {
                return [
                    RESPONSE_STATUS_KEY => false,
                    RESPONSE_MESSAGE_KEY => __("Total must be :minimumOrderTotal.", ['minimumOrderTotal' => $minimumTotal])
                ];
            }
        }

        DB::beginTransaction();

        try {

            if (!$this->_decreaseBalance()) {
                throw new Exception('Failed to update wallet.');
            }

            if (!$this->save()) {
                throw new Exception('Failed to create order');
            }

        } catch (Exception $exception) {

            DB::rollBack();
            Logger::error($exception, '[FAILED][CreateOrderService][create]');
            $message = $exception->getCode() === "22003" ?
                __('You don\'t have enough balance to place order.') : __('Failed to place order.');

            return [
                RESPONSE_STATUS_KEY => false,
                RESPONSE_MESSAGE_KEY => $message,
            ];
        }

        DB::commit();

        ProcessOrderJob::dispatch($this->order);
        broadcast(new OrderBroadcast($this->order));

        return [
            RESPONSE_STATUS_KEY => true,
            RESPONSE_MESSAGE_KEY => __("Your order has been placed successfully."),
            RESPONSE_DATA => [
                'order_id' => $this->order->id,
                'order_type' => $this->order->type,
                'price' => $this->order->price,
                'amount' => $this->order->amount,
                'total' => $this->order->total,
                'open_amount' => $this->order->amount,
                'exchanged' => $this->order->exchanged,
                'stop_limit' => $this->order->stop_limit,
                'date' => $this->order->created_at->toDateTimeString(),
                'category' => $this->order->category
            ]
        ];
    }

    private function setTradePair(string $coinPair): void
    {
        $this->tradePair = CoinPair::where('name', $coinPair)->with('coin', 'baseCoin')->first();
    }

    private function setWallet(): void
    {
        $this->wallet = Wallet::where('user_id', $this->user->id)
            ->where('is_system_wallet', INACTIVE)
            ->when($this->request->order_type === ORDER_TYPE_BUY, function ($query) {
                $query->where('symbol', $this->tradePair->base_coin);
            })
            ->when($this->request->order_type === ORDER_TYPE_SELL, function ($query) {
                $query->where('symbol', $this->tradePair->trade_coin);
            })
            ->first();
    }

    private function _decreaseBalance()
    {
        if ($this->request->order_type === ORDER_TYPE_BUY) {
            $this->decreaseBalance = $this->request->input('total');
        } else {
            $this->decreaseBalance = $this->request->amount;
        }

        $attributes = [
            'primary_balance' => DB::raw('primary_balance - ' . $this->decreaseBalance),
        ];

        return $this->wallet->update($attributes);
    }

    private function save()
    {
        $orderParams = [
            'user_id' => $this->user->id,
            'trade_coin' => $this->tradePair->trade_coin,
            'base_coin' => $this->tradePair->base_coin,
            'trade_pair' => $this->request->trade_pair,
            'category' => $this->request->category,
            'type' => $this->request->order_type,
            'price' => $this->request->get('price') ?: null,
            'amount' => $this->request->get('amount') ?: null,
            'total' => $this->request->input('total') ?: null,
            'stop_limit' => $this->request->get('stop') ?: null,
            'maker_fee_in_percent' => settings('exchange_maker_fee'),
            'taker_fee_in_percent' => settings('exchange_taker_fee'),
            'status' => $this->request->has('stop') ? STATUS_INACTIVE : STATUS_PENDING,
        ];

        return $this->order = Order::create($orderParams);
    }
}
