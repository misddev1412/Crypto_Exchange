<?php

namespace App\Models\Core;

use App\Override\Eloquent\LaraframeModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class UserActivity extends Model
{
    public $incrementing = false;
    protected $primaryKey = null;
    protected $fillable = [
        'user_id',
        'browser',
        'device',
        'operating_system',
        'location',
        'ip_address',
        'note',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
