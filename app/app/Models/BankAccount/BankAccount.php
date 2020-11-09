<?php

namespace App\Models\BankAccount;

use App\Models\Core\Country;
use App\Models\Deposit\WalletDeposit;
use App\Override\Eloquent\LaraframeModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BankAccount extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    protected $fillable = [
        'user_id',
        'reference_number',
        'bank_name',
        'iban',
        'swift',
        'account_holder',
        'bank_address',
        'account_holder_address',
        'is_verified',
        'is_active',
        'country_id',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function getBankAccountNameAttribute(): string
    {
        return $this->bank_name . ' - ' . $this->iban;
    }

    public function deposits()
    {
        return $this->hasMany(WalletDeposit::class, 'system_bank_account_id', 'id');
    }

    public function scopeWithDepositCount($query)
    {
        return $query->addSelect([
            'deposit_count' => WalletDeposit::select(DB::raw('COUNT(*)'))
                ->whereColumn('bank_accounts.id', 'system_bank_account_id')
        ]);
    }
}
