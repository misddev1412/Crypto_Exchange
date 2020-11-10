<?php

namespace App\Models\Core;

use App\Models\Coin\CoinPair;
use App\Override\Eloquent\LaraframeModel as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class UserPreference extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['user_id', 'default_language', 'default_coin_pair',];

    protected static function boot()
    {
        parent::boot();

        static::creating(static function ($model) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exchange()
    {
        return $this->belongsTo(CoinPair::class, 'default_coin_pair');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'default_language');
    }
}
