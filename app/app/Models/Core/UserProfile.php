<?php

namespace App\Models\Core;

use App\Override\Eloquent\LaraframeModel as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class UserProfile extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['user_id', 'first_name', 'last_name', 'address', 'phone'];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
        });
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
