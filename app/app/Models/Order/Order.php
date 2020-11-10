<?php

namespace App\Models\Order;

use App\Broadcasts\Exchange\OrderBroadcast;
use App\Jobs\Order\ProcessOrderJob;
use App\Models\Coin\Coin;
use App\Models\Coin\CoinPair;
use App\Models\Core\User;
use App\Override\Eloquent\LaraframeModel as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'user_id',
        'trade_coin',
        'base_coin',
        'trade_pair',
        'category', // limit, market, stop limit
        'type',
        'status',
        'price',
        'amount',
        'exchanged',
        'total',
        'canceled',
        'stop_limit',
        'maker_fee_in_percent',
        'taker_fee_in_percent',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
            $model->trade_pair = sprintf("%s_%s", $model->trade_coin, $model->base_coin);
        });
    }

    public function coinPair(): BelongsTo
    {
        return $this->belongsTo(CoinPair::class, 'trade_pair', 'name');
    }

    public function coin(): BelongsTo
    {
        return $this->belongsTo(Coin::class, 'trade_coin', 'symbol');
    }

    public function baseCoin(): BelongsTo
    {
        return $this->belongsTo(Coin::class, 'base_coin', 'symbol');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeStatusOpen($query)
    {
        return $query->where('status', STATUS_PENDING);
    }
}
