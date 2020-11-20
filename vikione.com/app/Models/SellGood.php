<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellGood extends Model
{
    protected $table = 'sell_goods';

    protected $fillable = ['buyer', 'seller', 'amount', 'details', 'status'];


    public function user_seller(){
        return $this->hasOne('App\Models\User', 'id', 'seller');
    }

    public function user_buyer(){
        return $this->hasOne('App\Models\User', 'id', 'buyer');
    }

}
