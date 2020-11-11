<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
   protected $table = 'languages';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'label', 'short', 'code'];

	/**
     *
     * Relation with user
     *
     * @version 1.0.1
     * @since 1.0
     * @return void
     */
    public function translate()
    {
        return $this->hasMany(Translate::class, 'name', 'code');
    }
}
