<?php

namespace App\Models\Core;

use App\Override\Eloquent\LaraframeModel as Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Role extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'slug';

    protected $fillable = ['name', 'permissions', 'accessible_routes'];

    public static function boot()
    {
        parent::boot();

        self::creating(static function ($model) {
            $model->{$model->getKeyName()} = Str::slug($model->name);
        });
    }

    public function getPermissionsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setPermissionsAttribute($value): void
    {
        $this->attributes['permissions'] = json_encode($value);
    }

    public function getAccessibleRoutesAttribute($value): array
    {
        return json_decode($value, true);
    }

    public function setAccessibleRoutesAttribute($value): void
    {
        $this->attributes['accessible_routes'] = json_encode($value);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'assigned_role');
    }
}
