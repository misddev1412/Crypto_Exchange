<?php
/**
 * GlobalMeta Model
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * GlobalMeta Model
 *
 * @since 1.0 @version 1.0
 */
class GlobalMeta extends Model
{
    /*
     * Table Name Specified
     */
    protected $table = 'global_metas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get value
     *
     * @version 1.0.0
     * @since 1.1.0
     */
    public static function get_value($name, $pid=null, $output=null)
    {
        if (!empty($pid)) {
            $result = self::where(['name' => $name, 'pid' => $pid])->first();
        } else {
            $result = self::where(['name' => $name])->first();
        }

        $return = (!empty($output) && isset($result->$output)) ? $result->$output : $result;
        
        return $return;
    }

    /**
     *
     * Save the meta
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public static function save_meta($name, $value = null, $pid = null, $extra = null)
    {
        if ($pid != null) {
            $meta = self::where(['name' => $name, 'pid' => $pid])->first();
        } else {
            $meta = self::where(['name' => $name])->first();
        }

        if ($meta == null) {
            $meta = new self();
            $meta->name = $name;
            if ($pid != null) {
                $meta->pid = $pid;
            }
        }

        if ($value != null) {
            $meta->value = $value;
            if ($extra != null) {
                $meta->extra = $extra;
            }
            $meta->save();
        }

        return $meta;
    }

    /**
     *
     * Get Super Admins
     *
     * @version 1.1.0
     * @since 1.0
     * @return void
     */
    public static function get_super_admins()
    {
        $super = self::where('name', 'site_super_admin')->get();
        $users = [];
        foreach ($super as $user) {
            if($user->value=='1'||$user->value=='access'){
                array_push($users, intval($user->pid));
            }
        }
        return array_unique($users);
    }

    /**
     *
     * Checking if auth user have access
     *
     * @version 1.0.0
     * @since 1.1.3
     * @return void
     */
    public static function has_access($type=null, $uid=null)
    {
        $user_id = ($uid) ? $uid : auth()->id();
        if (in_array($user_id, self::get_super_admins())) {
            return true;
        }

        $get_access = self::where(['pid' => $user_id, 'name' => 'manage_access'])->first();
        if(!empty($get_access)) {
            $access = json_decode($get_access->extra, true);
            return self::check_access($access, $type);
        } else {
            $access = array_values(json_decode(gws('manage_access_default', json_encode(['level' => ['none']])), true));
            return self::check_access($access[0], $type);
        }
        return [];
    }


    /**
     *
     * Check what access available
     *
     * @version 1.0.0
     * @since 1.1.3
     * @return void
     */
    public static function check_access($data, $type=null)
    {
        if(in_array('as_super_admin', $data)) {
            return true;
        } elseif(!empty($type)) {
            return (in_array($type, $data)) ?  [$type] : [];
        }
        return array_unique($data);
    }


    /**
     *
     * Relation with Users
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'pid', 'id');
    }

    /**
     *
     * Relation with Json Data
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function data()
    {
        return (is_json($this->value) ? json_decode($this->value) : $this->value);
    }
}
