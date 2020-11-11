<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'translates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'name', 'text', 'pages', 'group', 'panel', 'load'];
}
