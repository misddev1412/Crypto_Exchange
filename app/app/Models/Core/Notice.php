<?php

namespace App\Models\Core;

use App\Override\Eloquent\LaraframeModel as Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Notice extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['title', 'description', 'type', 'visible_type', 'start_at', 'end_at', 'is_active', 'created_by'];

    public static function boot()
    {
        parent::boot();
        static::creating(static function ($model) {
            $model->{$model->getKeyName()} = Str::uuid();
        });
        static::created(function ($notice) {
            $notice = $notice->fresh();
            $notices = Cache::get('notices', collect([]));
            if ($notice->is_active) {
                $notices->push($notice);
            }
            Cache::put('notices', $notices, $notice->created_at->diffInMinutes($notice->created_at->copy()->endOfDay()));
        });

        static::updated(function ($notice) {
            $notices = Cache::get('notices', collect([]));
            $date = Carbon::now();
            if ($notice->is_active && $notice->start_at <= $date && $notice->end_at >= $date) {
                if (!$notices->firstWhere('id', $notice->id)) {
                    $notices->push($notice);
                    Cache::put('notices', $notices, $date->diffInMinutes($date->copy()->endOfDay()));
                }
            } else {
                $notices = $notices->filter(function ($existNotice) use ($notice) {
                    return $existNotice->id != $notice->id;
                });
                Cache::put('notices', $notices, $date->diffInMinutes($date->copy()->endOfDay()));
            }
        });

    }

    public function scopeToday($query)
    {
        $startDate = Carbon::now();
        return $query->where(function ($q) use ($startDate) {
            $q->where('start_at', '<=', $startDate)
                ->where('end_at', '>=', $startDate);
        })->orWhere(function ($q) {
            $q->whereNull('start_at')
                ->whereNull('end_at');
        });
    }
}
