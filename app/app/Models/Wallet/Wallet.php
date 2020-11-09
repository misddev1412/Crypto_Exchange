<?php

namespace App\Models\Wallet;

use App\Models\Coin\Coin;
use App\Models\Core\User;
use App\Models\Deposit\WalletDeposit;
use App\Models\Order\Order;
use App\Models\Withdrawal\WalletWithdrawal;
use App\Override\Eloquent\LaraframeModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Wallet extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $casts = [
        'is_system_wallet' => 'boolean',
        'is_active' => 'boolean'
    ];

    protected $fillable = [
        'user_id',
        'symbol',
        'primary_balance',
        'on_order_balance',
        'address',
        'passphrase',
        'is_system_wallet',
        'is_active'
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
        });
    }

    public function setPassphraseAttribute($value): void
    {
        $this->attributes['passphrase'] = encrypt($value);
    }

    public function getPassphraseAttribute($value)
    {
        return is_null($value) ? $value : decrypt($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coin(): BelongsTo
    {
        return $this->belongsTo(Coin::class, 'symbol', 'symbol');
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(WalletDeposit::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(WalletWithdrawal::class);
    }

    public function scopeWithoutSystemWallet($query)
    {
        return $query->where('is_system_wallet', INACTIVE);
    }

    public function scopeSystemWallet($query)
    {
        return $query->where('is_system_wallet', ACTIVE);
    }

    public function scopeWithOnOrderBalance($query)
    {
        $orderTypeBuy = ORDER_TYPE_BUY;
        $orderTypeSell = ORDER_TYPE_SELL;
        return $query->addSelect([
            'on_order_balance' => Order::select(
                DB::raw("TRUNCATE(SUM(CASE
                    WHEN type = '{$orderTypeBuy}' AND base_coin = wallets.symbol THEN (amount-exchanged)*price
                    WHEN type = '{$orderTypeSell}' AND trade_coin = wallets.symbol  THEN (amount- exchanged)
                    ELSE 0 END
                ),8)")
            )
                ->whereIn('status', [STATUS_PENDING, STATUS_INACTIVE])
                ->where('user_id', Auth::id())
        ]);
    }
}
