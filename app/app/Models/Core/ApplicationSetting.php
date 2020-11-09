<?php

namespace App\Models\Core;

use App\Override\Eloquent\LaraframeModel as Model;
use Exception;

class ApplicationSetting extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'slug';
    protected $keyType = 'string';

    protected $fillable = [
        'slug',
        'value',
    ];

    public function getValueAttribute($value)
    {
        $fieldValue = '';

        try {
            $fieldValue = decrypt($value);
        } catch (Exception $exception) {
            $fieldValue = $value;
        }

        return $fieldValue;
    }
}
