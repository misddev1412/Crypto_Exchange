<?php

namespace App\Models\Exchange;

use App\Override\Eloquent\LaraframeModel as Model;

class ExchangeData extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'trade_pair';
    public $timestamps = false;

    protected $fillable = ['trade_pair', '5min', '15min', '30min', '2hr', '4hr', '1day','date'];
}
