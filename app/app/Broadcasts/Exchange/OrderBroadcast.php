<?php

    namespace App\Broadcasts\Exchange;

    use App\Models\Order\Order;
    use Illuminate\Broadcasting\Channel;
    use Illuminate\Broadcasting\InteractsWithSockets;
    use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
    use Illuminate\Foundation\Events\Dispatchable;
    use Illuminate\Queue\SerializesModels;

    class OrderBroadcast implements ShouldBroadcastNow
    {
        use Dispatchable, InteractsWithSockets, SerializesModels;

        public $order;

        public function __construct(Order $order)
        {
            $this->order = $order->withoutRelations();
        }

        public function broadcastOn()
        {
            return new Channel(get_channel_prefix(). 'order.' . $this->order->trade_pair);
        }

        /**
         * Determine if this event should broadcast.
         *
         * @return bool
         */
        public function broadcastWhen()
        {
            return ($this->order->status == STATUS_PENDING && $this->order->category !== ORDER_CATEGORY_MARKET);
        }

        public function broadcastAs()
        {
            return 'order.created';
        }

        public function broadcastWith()
        {
            return [
                'type' => $this->order->type,
                'price' => $this->order->price,
                'amount' => $this->order->amount,
                'total' => bcmul($this->order->price, $this->order->amount),
                'trade_pair' => $this->order->trade_pair,
            ];
        }
    }
