<?php

namespace App\Models\Withdrawal;

use App\Jobs\Withdrawal\WithdrawalProcessJob;
use App\Mail\Withdrawal\Confirmation;
use App\Models\BankAccount\BankAccount;
use App\Models\Coin\Coin;
use App\Models\Core\Notification;
use App\Models\Core\User;
use App\Models\Wallet\Wallet;
use App\Override\Eloquent\LaraframeModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class WalletWithdrawal extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'wallet_id',
        'symbol',
        'amount',
        'system_fee',
        'address',
        'txn_id',
        'api',
        'bank_account_id',
        'status',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
        });

        static::created(static function ($model) {
            if (settings('is_email_confirmation_required')) {
                Mail::to($model->user->email)->send(new Confirmation($model));
            } else if ($model->status === STATUS_PENDING) {
                WithdrawalProcessJob::dispatch($model);
            }
        });

        static::updated(static function ($model) {
            if ($model->status === STATUS_COMPLETED) {
                $message = __("Your withdrawal request of :amount :coin has been completed.", ['amount' => $model->amount, 'coin' => $model->symbol]);
            } else if ($model->status === STATUS_CANCELED) {
                $message = __("Your withdrawal request of :amount :coin was canceled. The amount has been refunded to your wallet.", ['amount' => $model->amount, 'coin' => $model->symbol]);
            } else if ($model->status === STATUS_FAILED) {
                $message = __("Your withdrawal request of :amount :coin was failed. The amount has been refunded to your wallet. ", ['amount' => $model->amount, 'coin' => $model->symbol]);
            }

            if (isset($message)) {
                Notification::create([
                    'user_id' => $model->user_id,
                    'message' => __("Your withdrawal request of :amount :coin has been completed.", ['amount' => $model->amount, 'coin' => $model->symbol])
                ]);
            }
        });
    }

    public function coin(): BelongsTo
    {
        return $this->belongsTo(Coin::class, 'symbol', 'symbol');
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function getRecipientWallet()
    {
        return Wallet::where('address', $this->address)
            ->where('symbol', $this->symbol)
            ->where('is_system_wallet', INACTIVE)
            ->where('is_active', ACTIVE)
            ->first();
    }
}
