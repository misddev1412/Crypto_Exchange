<?php
/**
 * User Model
 *
 * Store the users meta data
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0
 */
namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{

    /*
     * Table Name Specified
     */
    protected $table = 'user_metas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['userId'];

    //define database column constants
    const FIELD_USER_ID = "userId";
    const NOTIFY_ADMIN = "notify_admin";
    const NEWS_LETTER = "newsletter";
    const UNUSUAL_ACTIVITY = "unusual";
    const ACTIVITY_LOG = "save_activity";
    const FIELD_MAIL_IF_PWD_CHNG = "pwd_chng";

    /**
     * get user meta by user id
     * @param int $userId
     * @return Array Object $userMeta
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public static function getMeta($userId)
    {
        $userMeta = self::where('userId', $userId)->first();
        if ($userMeta === null) {
            $makeMeta = array(
                'userId' => Auth::id(),
                self::NOTIFY_ADMIN => 0,
                self::NEWS_LETTER => 0,
                self::UNUSUAL_ACTIVITY => 1,
                self::ACTIVITY_LOG => "TRUE",
                self::FIELD_MAIL_IF_PWD_CHNG => "FALSE",
            );

            self::insert($makeMeta);
            $userMeta = (object) $makeMeta;
        }
        return $userMeta;
    }

    /**
     *
     * Relation with user
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'userId', 'id');
    }
}
