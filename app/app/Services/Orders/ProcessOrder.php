<?php

namespace App\Services\Orders;

use App\Broadcasts\Exchange\ExchangeBroadcast;
use App\Broadcasts\Exchange\SettlementOrdersBroadcast;
use App\Jobs\Order\ProcessStopLimit;
use App\Models\Exchange\Exchange;
use App\Models\Order\Order;
use App\Models\Referral\ReferralEarning;
use App\Models\Wallet\Wallet;
use App\Services\Logger\Logger;
use Exception;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

trait ProcessOrder
{
    public function startProcessing()
    {
        $oppositeOrders = $this->getOppositeOrdersCursor();
        foreach ($oppositeOrders->cursor() as $oppositeOrder) {
            //Exit the loop if processing returns false
            if (!$this->oppositeOrderProcessing($oppositeOrder)) {
                break;
            }
        }
    }

    private function getOppositeOrdersCursor()
    {
        $tradableType = $this->isBuyOrder ? ORDER_TYPE_SELL : ORDER_TYPE_BUY;
        $sort = $this->isBuyOrder ? 'asc' : 'desc';
        $operator = $this->isBuyOrder ? '<=' : '>=';
        $categories = [ORDER_CATEGORY_LIMIT, ORDER_CATEGORY_STOP_LIMIT];

        return Order::query()
            ->whereIn('category', $categories)
            ->where('type', $tradableType)
            ->where('trade_pair', $this->order->getRawOriginal('trade_pair'))
            ->when($this->order->price, function ($query) use ($operator) {
                $query->where('price', $operator, $this->order->price);
            })
            ->where('status', STATUS_PENDING)
            ->orderBy('price', $sort)
            ->orderBy('created_at');
    }

    private function settlementOrder($order)
    {
        $remainingAmount = bcsub($order->amount, $order->exchanged);
        $remainingTotal = 0;
        //Update order attributes with canceled amount and status completed
        if ($this->ordersAttributes->where('conditions.id', $order->id)->count()) {
            $this->ordersAttributes->transform(function ($orderAttribute) use ($order, &$remainingAmount, &$remainingTotal) {
                if ($orderAttribute['conditions']['id'] == $order->id) {
                    $remainingAmount = bcsub($remainingAmount, $orderAttribute['fields']['exchanged'][1]);
                    $remainingTotal = bcmul($remainingAmount, $order->price);
                    if (bccomp($remainingTotal, '0') <= 0) {
                        $orderAttribute['fields']['canceled'] = ['increment', $remainingAmount];
                        $orderAttribute['fields']['status'] = STATUS_COMPLETED;
                    }
                }
                return $orderAttribute;
            });

        } else {
            $remainingTotal = bcmul($remainingAmount, $order->price);
            if (bccomp($remainingTotal, '0') <= 0) {
                $this->ordersAttributes->push([
                    'conditions' => ['id' => $order->id, 'status' => STATUS_PENDING],
                    'fields' => [
                        'status' => STATUS_COMPLETED,
                        'canceled' => ['increment', $remainingAmount]
                    ]
                ]);
            }
        }

        if (bccomp($remainingTotal, '0') <= 0) {
            //Update the given order's user's wallet
            $this->makeWalletsAttributes(
                $order->user_id,
                $this->getOutgoingCoinSymbol($order),
                $order->type === ORDER_TYPE_BUY ? $remainingTotal : $remainingAmount
            );

            if (bccomp($remainingAmount, '0') > 0) {
                $this->broadcastOrderSettlementAttributes->push([
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'category' => $order->category,
                    'type' => $order->type,
                    'price' => $order->price,
                    'amount' => $remainingAmount,
                    'total' => $remainingTotal,
                    'date' => $this->date->unix()
                ]);
            }
        }
    }

    /**
     * If the given user wallet exists in the wallets attributes
     * then find the wallet and update the primary balance
     * otherwise push the amount to the given user wallet
     * @param $userId
     * @param $symbol
     * @param $amount
     * @param int $isSystemWallet
     */
    private function makeWalletsAttributes($userId, $symbol, $amount, $isSystemWallet = INACTIVE)
    {
        //If the amount is less than or equal to 0 then skip wallet update
        if (bccomp($amount, '0') <= 0) {
            return;
        }

        if ($this->walletExistsInWalletsAttributes($userId, $symbol, $isSystemWallet) > 0) {
            $this->walletsAttributes->transform(function ($wallet) use ($userId, $symbol, $amount, $isSystemWallet) {
                if (
                    $wallet['conditions']['user_id'] == $userId &&
                    $wallet['conditions']['symbol'] == $symbol &&
                    $wallet['conditions']['is_system_wallet'] === $isSystemWallet
                ) {
                    $wallet['fields']['primary_balance'][1] = bcadd($wallet['fields']['primary_balance'][1], $amount);
                }
                return $wallet;
            });
        } else {
            $this->walletsAttributes->push([
                'conditions' => [
                    'user_id' => $userId,
                    'symbol' => $symbol,
                    'is_system_wallet' => $isSystemWallet
                ],
                'fields' => [
                    'primary_balance' => ['increment', $amount],
                ]
            ]);
        }
    }

    private function walletExistsInWalletsAttributes($userId, $symbol, $isSystemWallet)
    {
        return $this->walletsAttributes
            ->where('conditions.user_id', $userId)
            ->where('conditions.symbol', $symbol)
            ->where('conditions.is_system_wallet', $isSystemWallet)
            ->count();
    }

    /**
     * If the given order is buy then the outgoing coin will be the base coin
     * otherwise the outgoing coin will be the trade coin
     * @param $order
     * @return mixed
     */
    private function getOutgoingCoinSymbol($order)
    {
        return $order->type === ORDER_TYPE_BUY ? $order->base_coin : $order->trade_coin;
    }

    private function calculateTradeFee($order, $amount, $total, $isMaker)
    {
        $feePercent = $isMaker ? $order->maker_fee_in_percent : $this->order->taker_fee_in_percent;
        return bcdiv(bcmul($order->type === ORDER_TYPE_BUY ? $amount : $total, $feePercent), '100');
    }

    private function giveReferralEarningToReferrer($order, $fee)
    {
        //Push the order user's referrer's referral earning attributes for insert
        $referralEarning = $this->makeReferralEarningsAttributes(
            $order->user->referrer_id,
            $order->user_id,
            $this->getIncomingCoinSymbol($order),
            $fee
        );

        //If referral earning is greater than 0 then
        if (bccomp($referralEarning, '0') > 0) {
            //Push the order user's referrer's referral earning to the referrer's wallet
            $this->makeWalletsAttributes(
                $this->order->user->referrer_id,
                $this->getIncomingCoinSymbol($this->order),
                $referralEarning
            );
        }

        return $referralEarning;
    }

    private function makeReferralEarningsAttributes($referrerUserId, $referralUserId, $symbol, $fee)
    {
        $referralEarning = 0;
        //If the given order user has a referrer then
        if ($referrerUserId) {
            //Calculate the referral earning from the given fee and referral percentage
            $referralEarning = $this->calculateReferralEarning($fee);

            //If the referral earning is greater than 0 then
            if (bccomp($referralEarning, '0') > 0) {
                //Push referral earning attributes to referral earning history
                $this->referralEarningsAttributes->push([
                    'referrer_user_id' => $referrerUserId,
                    'referral_user_id' => $referralUserId,
                    'symbol' => $symbol,
                    'amount' => $referralEarning,
                    'created_at' => $this->date,
                    'updated_at' => $this->date,
                ]);
            }
        }
        return $referralEarning;
    }

    private function calculateReferralEarning($amount)
    {
        return bcdiv(bcmul($amount, $this->settings['referral_percentage']), "100");
    }

    /**
     * If the given order is buy then the incoming coin will be the trade coin
     * otherwise the incoming coin will be the base coin
     * @param $order
     * @return mixed
     */
    private function getIncomingCoinSymbol($order)
    {
        return $order->type === ORDER_TYPE_BUY ? $order->trade_coin : $order->base_coin;
    }

    /**
     * Push exchange attributes for insert
     * @param $order
     * @param $amount
     * @param $fee
     * @param $referralEarning
     * @param $isMaker
     * @param $oppositeOrder
     */

    private function makeExchangesAttributes($order, $amount, $fee, $referralEarning, $isMaker, $oppositeOrder)
    {
        $price = $order->price === null ? $oppositeOrder->price : $order->price;

        $this->exchangesAttributes->push([
            'id' => Uuid::uuid4(),
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'trade_coin' => $order->trade_coin,
            'base_coin' => $order->base_coin,
            'trade_pair' => $order->getRawOriginal('trade_pair'),
            'amount' => $amount,
            'price' => $price,
            'total' => bcmul($amount, $price),
            'fee' => $fee,
            'referral_earning' => $referralEarning,
            'order_type' => $order->type,
            'related_order_id' => $oppositeOrder->id,
            'is_maker' => $isMaker,
            'created_at' => $this->date,
            'updated_at' => $this->date,
        ]);
    }

    private function close()
    {
        DB::beginTransaction();
        try {

            //Update the orders attributes
            $ordersUpdatedCount = Order::bulkUpdate($this->ordersAttributes->toArray());
            if ($ordersUpdatedCount != $this->ordersAttributes->count()) {
                throw new Exception("Could not update the orders");
            }

            //Insert the exchanges attributes
            $insertedExchangeCount = Exchange::insert($this->exchangesAttributes->toArray());
            if ($insertedExchangeCount != $this->exchangesAttributes->count()) {
                throw new Exception("Could not insert the exchanges");
            }

            //Update the wallets attributes
            $walletsUpdateCount = Wallet::bulkUpdate($this->walletsAttributes->toArray());
            if ($walletsUpdateCount != $this->walletsAttributes->count()) {
                throw new Exception("Could not update the wallets");
            }

            //Insert the referral earning attributes
            if ($this->referralEarningsAttributes->isNotEmpty()) {
                $insertedReferralEarningCount = ReferralEarning::insert($this->referralEarningsAttributes->toArray());
                if ($insertedReferralEarningCount != $this->referralEarningsAttributes->count()) {
                    throw new Exception("Could not insert the referral earnings");
                }
            }

            //Update last price to coin pair
            if (!$this->order->coinPair()->update(['last_price' => $this->exchangeLastPrice])) {
                throw new Exception("Could not update the last price");
            }
        } catch (Exception $exception) {
            DB::rollBack();
            Logger::error($exception, "[FAILED][ProcessOrder][close]");
            return;
        }
        DB::commit();

        //Process Stop Limit Orders
        ProcessStopLimit::dispatch($this->order->trade_pair, $this->exchangeLastPrice);

        //Broadcast the exchanged orders

        ExchangeBroadcast::broadcast($this->order->trade_pair, $this->broadcastOrdersAttributes->toArray());

        if ($this->broadcastOrderSettlementAttributes->isNotEmpty()) {
            SettlementOrdersBroadcast::broadcast($this->order->trade_pair, $this->broadcastOrderSettlementAttributes->toArray());
        }

        return;
    }
}
