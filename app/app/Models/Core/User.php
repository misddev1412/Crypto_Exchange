<?php

namespace App\Models\Core;

use App\Jobs\Wallet\GenerateUserWalletsJob;
use App\Models\BankAccount\BankAccount;
use App\Models\Exchange\Exchange;
use App\Models\Kyc\KycVerification;
use App\Models\Order\Order;
use App\Models\Wallet\Wallet;
use App\Override\Eloquent\LaraframeModel as Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\{Auth\Access\Authorizable as AuthorizableContract,
    Auth\Authenticatable as AuthenticatableContract,
    Auth\CanResetPassword as CanResetPasswordContract
};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\Sanctum;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail, HasApiTokens, HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'referrer_id',
        'assigned_role',
        'username',
        'password',
        'email',
        'referral_code',
        'remember_me',
        'avatar',
        'google2fa_secret',
        'is_email_verified',
        'is_financial_active',
        'is_accessible_under_maintenance',
        'is_super_admin',
        'status',
        'created_by'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model) {
            $model->{$model->getKeyName()} = Str::uuid();
        });
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id', 'id');
    }

    public function referralUsers()
    {
        return $this->hasMany(User::class, 'referrer_user_id', 'id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'assigned_role');
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function preference()
    {
        return $this->hasOne(UserPreference::class);
    }

    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    public function kycVerifications(): HasMany
    {
        return $this->hasMany(KycVerification::class);
    }

    public function kycVerification($status = STATUS_VERIFIED): HasOne
    {
        return $this->hasOne(KycVerification::class)->where('status', $status);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tradeHistories()
    {
        return $this->hasMany(Exchange::class, 'user_id');
    }

    public function banks()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function scopeSuperAdmin($query, $isSuperAdmin = ACTIVE)
    {
        return $query->where('is_super_admin', $isSuperAdmin)
            ->where('assigned_role', USER_ROLE_ADMIN);
    }

    public function isSuperAdmin()
    {
        return $this->is_super_admin;
    }

    /**
     * Get the access tokens that belong to model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function tokens()
    {
        return $this->morphMany(Sanctum::$personalAccessTokenModel, 'tokenable');
    }
}
