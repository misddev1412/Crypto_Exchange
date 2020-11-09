<?php

namespace App\Models\Core;

use App\Jobs\Wallet\GenerateUserWalletsJob;
use App\Override\Eloquent\LaraframeModel as Model;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Notification extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['user_id', 'message', 'read_at'];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model) {
            $model->{$model->getKeyName()} = Str::uuid();
        });
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function markAsRead()
    {
        return $this->update(['read_at' => Carbon::now()]);
    }

    public function markAsUnread()
    {
        return $this->update(['read_at' => null]);
    }
}
