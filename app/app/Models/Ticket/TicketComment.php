<?php

namespace App\Models\Ticket;

use App\Models\Core\User;
use Carbon\Carbon;
use App\Override\Eloquent\LaraframeModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketComment extends Model
{
    protected $fillable = ['user_id', 'ticket_id', 'content', 'attachment'];

    public function setCreatedAt($value): void
    {
        $this->attributes['created_at'] = $value ?: Carbon::now();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
