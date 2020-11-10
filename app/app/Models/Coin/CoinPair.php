<?php

namespace App\Models\Coin;

use App\Models\Exchange\Exchange;
use App\Override\Eloquent\LaraframeModel as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CoinPair extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = 'name';
    protected $keyType = 'string';

    protected $fillable = [
        'trade_coin',
        'base_coin',
        'is_active',
        'is_default',
        'last_price',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model) {
            $model->{$model->getKeyName()} = sprintf("%s_%s", $model->trade_coin, $model->base_coin);
        });

        static::updating(static function ($model) {
            $model->{$model->getKeyName()} = sprintf("%s_%s", $model->trade_coin, $model->base_coin);
        });
    }

    public function coin(): BelongsTo
    {
        return $this->belongsTo(Coin::class, 'trade_coin', 'symbol');
    }

    public function baseCoin(): BelongsTo
    {
        return $this->belongsTo(Coin::class, 'base_coin', 'symbol');
    }

    public function getTradePairAttribute(): string
    {
        return Str::replaceFirst("_", "/", $this->name);
    }

    public function exchanges(): HasMany
    {
        return $this->hasMany(Exchange::class, 'trade_pair', 'name');
    }

    public function exchangeSummary()
    {
        $exchange = new Exchange();
        $tableName = $exchange->getTable();

        return $this->hasOne(Exchange::class, 'trade_pair', 'name')
            ->selectRaw('trade_pair, MIN(price) as low_price, MAX(price) as high_price')
            ->selectRaw('TRUNCATE(SUM(amount),8) as trade_coin_volume')
            ->selectRaw('TRUNCATE(SUM(amount * price),8) as base_coin_volume')
            ->addSelect([
                'first_price' => DB::table($tableName, 'ex')
                    ->select('price')
                    ->whereColumn('ex.trade_pair', $tableName . '.trade_pair')
                    ->where('ex.is_maker', ACTIVE)
                    ->where('created_at', '>=', now()->subDay())
                    ->limit(1)
            ])
            ->where('is_maker', ACTIVE)
            ->where('created_at', '>=', now()->subDay())
            ->groupBy('trade_pair');
    }
}
