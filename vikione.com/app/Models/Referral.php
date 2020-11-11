<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    /*
     * Table Name Specified
     */
    protected $table = 'referrals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'user_bonus', 'refer_by', 'refer_bonus', 'meta_data'
    ];
}
