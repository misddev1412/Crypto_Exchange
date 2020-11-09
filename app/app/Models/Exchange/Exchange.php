<?php

namespace App\Models\Exchange;

use App\Models\Coin\Coin;
use App\Models\Coin\CoinPair;
use App\Models\Order\Order;
use App\Override\Eloquent\LaraframeModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Exchange extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'user_id',
        'exchange_group_id',
        'order_id',
        'trade_coin',
        'base_coin',
        'amount',
        'price',
        'total',
        'fee',
        'referral_earning',
        'type',
        'related_order_id',
        'base_order',
        'is_maker',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function coin(): BelongsTo
    {
        return $this->belongsTo(Coin::class, 'trade_coin', 'symbol');
    }

    public function baseCoin() : BelongsTo
    {
        return $this->belongsTo(Coin::class, 'base_coin', 'symbol');
    }

    public function coinPair(): BelongsTo
    {
        return $this->belongsTo(CoinPair::class, 'trade_pair', 'name');
    }
}
