<?php

namespace App\Models\Core;

use App\Override\Eloquent\LaraframeModel as Model;

class Language extends Model
{
    protected $fillable = ['name', 'short_code', 'icon', 'is_active'];
}
