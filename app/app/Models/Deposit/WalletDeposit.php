<?php

namespace App\Models\Deposit;

use App\Models\BankAccount\BankAccount;
use App\Models\Coin\Coin;
use App\Models\Core\User;
use App\Models\Wallet\Wallet;
use App\Override\Eloquent\LaraframeModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class WalletDeposit extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'wallet_id',
        'symbol',
        'amount',
        'network_fee',
        'system_fee',
        'address',
        'txn_id',
        'api',
        'status',
        'bank_account_id',
        'system_bank_account_id',
        'receipt',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
        });
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function coin(): BelongsTo
    {
        return $this->belongsTo(Coin::class, 'symbol', 'symbol');
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function systemBankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'system_bank_account_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
