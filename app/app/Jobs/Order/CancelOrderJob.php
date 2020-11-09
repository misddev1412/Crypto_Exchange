<?php

    namespace App\Jobs\Order;

    use App\Broadcasts\Exchange\CancelOrderBroadcast;
    use App\Models\Order\Order;
    use App\Models\Wallet\Wallet;
    use App\Services\Logger\Logger;
    use Exception;
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Queue\SerializesModels;
    use Illuminate\Support\Facades\DB;

    class CancelOrderJob implements ShouldQueue
    {
        use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

        public $order;
        public $deleteWhenMissingModels = true;
        public $beforeChangeStatus = null;

        public function __construct(Order $order)
        {
            $this->queue = 'order-cancel';
            $this->order = $order->withoutRelations();
            $this->beforeChangeStatus = $this->order->status;
        }


        public function handle()
        {
            DB::beginTransaction();

            try {
                $canceledAmount = bcsub($this->order->amount, $this->order->exchanged);

                if ($this->order->type === ORDER_TYPE_BUY) {
                    $coin = $this->order->base_coin;
                    $returnBalance = bcmul($canceledAmount, $this->order->price);
                } else {
                    $coin = $this->order->trade_coin;
                    $returnBalance = $canceledAmount;
                }

                $orderAttributes = [
                    'canceled' => $canceledAmount,
                    'status' => STATUS_CANCELED
                ];

                if (!$this->order->update($orderAttributes)) {
                    throw new Exception("Failed to change state to cancel.");
                }

                $wallet = Wallet::where('user_id', $this->order->user_id)
                    ->where('symbol', $coin)
                    ->withoutSystemWallet()
                    ->first();

                if (!$wallet->increment('primary_balance', $returnBalance)) {
                    throw new Exception("Failed to update wallet for cancel order.");
                }

            } catch (Exception $e) {
                DB::rollBack();
                Logger::error($e, "[FAILED][CancelOrderJob][handle]");
                return;
            }

            DB::commit();

            CancelOrderBroadcast::broadcast($this->order, $this->beforeChangeStatus);
        }
    }
