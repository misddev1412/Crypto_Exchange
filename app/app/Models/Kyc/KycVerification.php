<?php

namespace App\Models\Kyc;

use App\Models\Core\User;
use App\Override\Eloquent\LaraframeModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KycVerification extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'user_id', 'type', 'card_image', 'status', 'reason'];

    public function setCardImageAttribute($value)
    {
        return $this->attributes['card_image'] = json_encode($value);
    }

    public function getCardImageAttribute()
    {
        return json_decode($this->attributes['card_image'], true);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
