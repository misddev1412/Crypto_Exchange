<?php
/**
 * Activity Model
 *
 * Store the activity of user
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    /*
     * Table Name Specified
     */
    protected $table = 'activities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'device', 'browser', 'ip',
    ];
}
