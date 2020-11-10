<?php

namespace App\Models\Post;

use App\Override\Eloquent\LaraframeModel as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PostCategory extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = 'slug';
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model) {
            $model->{$model->getKeyName()} = Str::slug($model->name);
        });

        static::updating(static function ($model) {
            $model->{$model->getKeyName()} = Str::slug($model->name);
        });
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
