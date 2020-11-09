<?php

namespace App\Models\Page;

use App\Override\Eloquent\LaraframeModel as Model;
use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;

class Page extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'slug';
    protected $keyType = 'string';

    protected $fillable = [
        'slug',
        'title',
        'content',
        'meta_description',
        'meta_keywords',
        'is_published',
    ];
    protected $fakeFields = [
        'slug',
        'title',
        'content',
        'meta_description',
        'meta_keywords',
        'is_published',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model) {
            $model->{$model->getKeyName()} = Str::slug($model->title);
        });

        static::updating(static function ($model) {
            $model->{$model->getKeyName()} = Str::slug($model->title);
        });
    }

    public function setMetaKeywordsAttribute($value): void
    {
        $this->attributes['meta_keywords'] = json_encode($value);
    }

    public function getMetaKeywordsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getContentAttribute($value)
    {
        return Purify::clean($value);
    }
}
