<?php
/**
 * IcoMeta Model
 *
 * Store the ICO Stages meta data
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0
 */
namespace App\Models;

use IcoData;
use Illuminate\Database\Eloquent\Model;

class IcoMeta extends Model
{
    /*
     * Table Name Specified
     */
    protected $table = 'ico_metas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stage_id', 'option_name', 'option_value',
    ];
    /**
     *
     * Get the data
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public static function get_data($stage_id, $type = 'price_option')
    {
        $meta_data = null;
        $meta = self::where(['stage_id' => $stage_id, 'option_name' => $type])->first();

        if ($meta) {
            $meta_data = is_json($meta->option_value) ? json_decode($meta->option_value) : $meta->option_value;
        } else {
            $meta_data = IcoData::default_ico_meta($type);
        }

        return (object) $meta_data;
    }
    /**
     *
     * Get the tire
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public static function get_tire($data = '')
    {
        if (is_array($data)) {
            if (isset($data->tires)) {
                $tires = $data->tires;
                return (object) $tires;
            }
        }
        if (is_json($data)) {
            return (object) json_decode($data);
        }
    }
}
