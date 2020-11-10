<?php

namespace App\Models\Post;

use App\Models\Core\User;
use App\Override\Eloquent\LaraframeModel as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;

class Post extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'category_slug',
        'content',
        'featured_image',
        'is_published',
        'is_featured',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
            $model->slug = Str::slug($model->title);
        });

        static::updating(static function ($model) {
            $model->slug = Str::slug($model->title);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function postCategory(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'category_slug', 'slug');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function getContentAttribute($value): string
    {
        return Purify::clean($value);
    }
}
