<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuySell extends Model
{
    protected $table = 'buy_sell';

    protected $fillable = ['buyer_id', 'seller_id', 'amount', 'method', 'status'];

    public function user_seller(){
        return $this->belongsTo('App\Models\User', 'seller_id', 'id');
    }

    public function user_buyer(){
        return $this->belongsTo('App\Models\User', 'buyer_id', 'id');
    }
}
