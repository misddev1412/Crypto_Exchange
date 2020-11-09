<?php

namespace App\Models\Coin;

use App\Jobs\CoinPair\DisableAssociatedCoinPairJob;
use App\Jobs\Wallet\GenerateUsersWalletsJob;
use App\Models\BankAccount\BankAccount;
use App\Models\Deposit\WalletDeposit;
use App\Models\Wallet\Wallet;
use App\Models\Withdrawal\WalletWithdrawal;
use App\Override\Eloquent\LaraframeModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Coin extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'symbol';
    protected $keyType = 'string';

    protected $fillable = [
        'symbol',
        'name',
        'type',
        'icon',
        'exchange_status',
        'deposit_status',
        'deposit_fee',
        'deposit_fee_type',
        'minimum_deposit_amount',
        'total_deposit',
        'total_deposit_fee',
        'withdrawal_status',
        'withdrawal_fee',
        'withdrawal_fee_type',
        'minimum_withdrawal_amount',
        'daily_withdrawal_limit',
        'total_withdrawal',
        'total_withdrawal_fee',
        'api',
        'is_active',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::updated(static function ($model) {
            if ($model->wasChanged('is_active') && $model->is_active) {
                if (env('QUEUE_CONNECTION', 'sync') === 'sync') {
                    GenerateUsersWalletsJob::dispatchNow($model);
                } else {
                    GenerateUsersWalletsJob::dispatch($model);
                }
            }

            if ($model->wasChanged('is_active') && $model->is_active == INACTIVE) {
                DisableAssociatedCoinPairJob::dispatchNow($model);
            }
        });
    }

    public function baseCoinPairs(): HasMany
    {
        return $this->hasMany(CoinPair::class, 'base_coin', 'symbol');
    }

    public function coinPairs(): HasMany
    {
        return $this->hasMany(CoinPair::class, 'coin', 'symbol');
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function systemWallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'symbol')->where('is_system_wallet', ACTIVE);
    }

    public function getApiAttribute($value): array
    {
        return !is_null($value) ? json_decode($value, true) : [];
    }

    public function setApiAttribute($value): void
    {
        $this->attributes['api'] = json_encode($value);
    }

    public function getAssociatedApi($api = null)
    {
        if (isset($this->api['selected_apis'])) {
            if ($this->type === COIN_TYPE_FIAT && $api && in_array($api, $this->api['selected_apis'])) {
                return app($api, [$this->symbol]);
            } else if ($this->type === COIN_TYPE_CRYPTO) {
                return app($this->api['selected_apis'], [$this->symbol]);
            }
        }
        return null;
    }

    public function deposits()
    {
        return $this->hasMany(WalletDeposit::class, 'symbol', 'symbol');
    }

    public function withdrawals()
    {
        return $this->hasMany(WalletWithdrawal::class, 'symbol', 'symbol');
    }

}
