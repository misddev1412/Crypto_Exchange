<?php

namespace Database\Factories\Order;

use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'user_id' => $this->faker->uuid,
            'trade_coin' => 'BTC',
            'base_coin' => 'USD',
            'category' => ORDER_CATEGORY_LIMIT,
            'type' => $this->faker->randomElement([ORDER_TYPE_BUY, ORDER_TYPE_SELL]),
            'price' => $this->faker->randomFloat(6000, 8000),
            'amount' => $this->faker->randomFloat(0.001, 2),
            'status' => STATUS_PENDING
        ];
    }
}
