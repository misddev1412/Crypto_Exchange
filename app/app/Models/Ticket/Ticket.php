<?php

namespace App\Models\Ticket;

use App\Models\Core\User;
use App\Override\Eloquent\LaraframeModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Ticket extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['user_id', 'assigned_to', 'ticket_id', 'title', 'content', 'attachment', 'status'];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function changeStatus($status, $assignedTo = null): bool
    {
        if (!in_array($this->status, [STATUS_OPEN, STATUS_PROCESSING])) {
            return false;
        }

        $params = [
            'status' => $status
        ];

        if ($assignedTo !== null) {
            $params['assigned_to'] = $assignedTo;
        }

        return $this->update($params);
    }
}
