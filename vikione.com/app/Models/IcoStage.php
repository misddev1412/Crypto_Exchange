<?php
/**
 * IcoStage Model
 *
 *  Manage the ICO Stage data
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.1.5
 */
namespace App\Models;

use Carbon\Carbon;
use App\Models\IcoMeta;
use Illuminate\Database\Eloquent\Model;

class IcoStage extends Model
{
    /*
     * Table Name Specified
     */
    protected $table = 'ico_stages';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'start_date', 'end_date', 'total_tokens', 'base_price', 'display_mode'];

    public function getSoldOutAttribute()
    {
        return $this->tnx_any()->where('status', 'approved')->whereNotIn('tnx_type', ['refund'])->sum('total_tokens');
    }

    public function getSoldLockAttribute()
    {
        return $this->tnx_purchase()->where('status', 'pending')->sum('total_tokens');
    }
    
    /**
     *
     * Relation with transactions
     *
     * @version 1.0.0
     * @since 1.1.2
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function tnx_any()
    {
        return $this->hasMany(Transaction::class, 'stage', 'id')
                    ->where(['refund' => null])->whereNotIn('status', ['deleted', 'new'])->orderBy('id', 'DESC');
                    // ->select('id', 'status', 'tnx_id', 'tnx_type', 'user', 'stage', 'tokens', 'total_tokens', 'total_bonus', 'amount', 'currency', 'base_currency', 'base_amount', 'payment_method', 'tnx_time', 'added_by')
    }

    /**
     *
     * Relation with transactions @purchased
     *
     * @version 1.0.0
     * @since 1.1.2
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function tnx_purchase()
    {
        return $this->tnx_any()->where(['tnx_type' => 'purchase']);
    }

    /**
     *
     * Relation with transactions @refund
     *
     * @version 1.0.0
     * @since 1.1.2
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function tnx_refund()
    {
        return $this->tnx_any()->where(['tnx_type' => 'refund']);
    }

    /**
     *
     * Relation with transactions  @referral
     *
     * @version 1.0.0
     * @since 1.1.2
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function tnx_referral()
    {
        return $this->tnx_any()->where(['tnx_type' => 'referral']);
    }


    /**
     *
     * Relation with transactions  @bonus
     *
     * @version 1.0.0
     * @since 1.1.2
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function tnx_bonus()
    {
        return $this->tnx_any()->where(['tnx_type' => 'bonus']);
    }


    /**
     *
     * Relation with user transactions
     *
     * @version 1.0.0
     * @since 1.1.2
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function tnx_by_user()
    {
        return $this->hasMany(Transaction::class, 'stage', 'id')->has('user_tnx');
    }

    /**
     *
     * Relation with Meta
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function ico_meta($what = null, $which = null)
    {
        if ($what == null) {
            return $this->belongsTo('App\Models\IcoMeta', 'id', 'stage_id');
        } else {
            $meta = IcoMeta::where('stage_id', $this->id)->where('option_name', $what)->first();
            if ($which != null) {
                $res = json_decode($meta->option_value);
                return (isset($res->$which) ? $res->$which : $res);
            }
            return $meta;
        }

    }

    /**
     *
     * Dashboard data
     *
     * @version 1.1
     * @since 1.0
     * @return void
     */
    public static function dashboard()
    {
        $tnxs = self::check_stage(active_stage()->id);

        $data['stage'] = active_stage();

        $data['trnxs'] = (object) [
            'all' => Transaction::where(['status' => 'approved', 'stage' => active_stage()->id])->count(),
            'pending' => Transaction::where(['status' => 'pending', 'stage' => active_stage()->id])->sum('total_tokens'),
            'percent' => to_percent(active_stage()->soldout, active_stage()->total_tokens),
            'last_week' => $tnxs->tokens,
        ];

        $data['totalSummary'] = self::summary();

        $data['phase'] = self::ico_phase();

        return (object) $data;
    }

    /**
     *
     * ICO Phase
     *
     * @version 1.2
     * @since 1.0
     * @return void
     */
    public static function ico_phase()
    {
        $all = self::orderBy('created_at', 'DESC')->get();
        $data = [];
        foreach ($all as $ico) {
            $ico->extra = (object) [
                'usd' => Transaction::amount_count('usd', ['stage' => $ico->id]),
                'eur' => Transaction::amount_count('eur', ['stage' => $ico->id]),
                'gbp' => Transaction::amount_count('gbp', ['stage' => $ico->id]),
                'cad' => Transaction::amount_count('cad', ['stage' => $ico->id]),
                'aud' => Transaction::amount_count('aud', ['stage' => $ico->id]),
                'try' => Transaction::amount_count('try', ['stage' => $ico->id]),
                'rub' => Transaction::amount_count('rub', ['stage' => $ico->id]),
                'inr' => Transaction::amount_count('inr', ['stage' => $ico->id]),
                'brl' => Transaction::amount_count('brl', ['stage' => $ico->id]),
                'nzd' => Transaction::amount_count('nzd', ['stage' => $ico->id]),
                'pln' => Transaction::amount_count('pln', ['stage' => $ico->id]),
                'jpy' => Transaction::amount_count('jpy', ['stage' => $ico->id]),
                'myr' => Transaction::amount_count('myr', ['stage' => $ico->id]),
                'idr' => Transaction::amount_count('idr', ['stage' => $ico->id]),
                'ngn' => Transaction::amount_count('ngn', ['stage' => $ico->id]),
                'eth' => Transaction::amount_count('eth', ['stage' => $ico->id]),
                'btc' => Transaction::amount_count('btc', ['stage' => $ico->id]),
                'ltc' => Transaction::amount_count('ltc', ['stage' => $ico->id]),
                'xrp' => Transaction::amount_count('xrp', ['stage' => $ico->id]),
                'xlm' => Transaction::amount_count('xlm', ['stage' => $ico->id]),
                'bch' => Transaction::amount_count('bch', ['stage' => $ico->id]),
                'bnb' => Transaction::amount_count('bnb', ['stage' => $ico->id]),
                'usdt' => Transaction::amount_count('usdt', ['stage' => $ico->id]),
                'trx' => Transaction::amount_count('trx', ['stage' => $ico->id]),
                'usdc' => Transaction::amount_count('usdc', ['stage' => $ico->id]),
                'dash' => Transaction::amount_count('dash', ['stage' => $ico->id]),
                'waves' => Transaction::amount_count('waves', ['stage' => $ico->id]),
                'xmr' => Transaction::amount_count('xmr', ['stage' => $ico->id]),
            ];
            array_push($data, $ico);
        }
        return (object) $data;
    }

    /**
     *
     * Dashboard total tokens summary
     *
     * @version 1.0.1
     * @since 1.0
     * @return void
     */
    public static function summary($id = '')
    {
        $all = $sold = $amount = 0;
        if ($id == '') {
            $is = self::where('status', '!=', 'deleted')->get();
            foreach ($is as $i) {
                $all += $i->total_tokens;
                $sold += $i->soldout;
                $amount += self::check_stage($i->id)->amount;
            }
        }
        $data['all'] = $all;
        $data['sold'] = $sold;
        $data['percent'] = ceil((($sold) * 100) / $all);
        $data['amount'] = $amount;
        return (object) $data;
    }

    /**
     *
     * Get stage wise summary
     *
     * @version 1.0.0
     * @since 1.1.2
     * @return void
     */
    public static function get_stages($stg=null)
    {
        $stages = (!empty($stg)) ? self::where('id', $stg)->whereNotIn('status', ['deleted'])->get() : self::whereNotIn('status', ['deleted'])->get();
        $data = [];

        if($stages) {
            foreach ($stages as $stage) {
                $tnxStage = $stage->tnx_any->where('status', 'approved')->whereNotIn('tnx_type', ['refund']);
                $tnxPurchase = $stage->tnx_purchase->where('status', 'approved');
                $tnxPending = $stage->tnx_purchase->where('status', 'pending');
                $tnxReferral = $stage->tnx_referral->where('status', 'approved');
                $tnxBonuses = $stage->tnx_bonus->where('status', 'approved');
                $data['stage_'.$stage->id] = (object) [
                    'id'        => $stage->id,
                    'name'      => $stage->name,
                    'tokens'    => $stage->total_tokens,
                    'sales'     => $stage->sales_token,
                    'amount'    => $stage->sales_amount,
                    'status'    => $stage->status,

                    'sold'      => $tnxStage->sum('total_tokens'),
                    'unsold'    => ($stage->total_tokens - $tnxStage->sum('total_tokens')),
                    'pending'   => $tnxPending->sum('total_tokens'),
                    'percent'   => to_percent($tnxStage->sum('total_tokens'), $stage->total_tokens),

                    'token_sale'     => $tnxPurchase->sum('tokens'),
                    'token_bonus_bb' => $tnxPurchase->sum('bonus_on_base'),
                    'token_bonus_ta' => $tnxPurchase->sum('bonus_on_token'),
                    'purchase_bonus' => $tnxPurchase->sum('total_bonus'),
                    'purchase'       => $tnxPurchase->sum('total_tokens'),
                    'referral'       => $tnxReferral->sum('total_tokens'),
                    'bonus'          => $tnxBonuses->sum('total_tokens'),
                    'contribute'     => $tnxPurchase->sum('base_amount'),
                    'contribute_in'  => self::in_currency($tnxPurchase)
                ];
            }
        }
        return (!empty($stg)) ? $data['stage_'.$stg] : $data;
    }
    
    /**
     *
     * Check the stage transaction
     *
     * @version 1.1
     * @since 1.0
     * @return void
     */
    public static function check_stage($stageId)
    {

        Carbon::setWeekStartsAt(Carbon::MONDAY);
        Carbon::setWeekEndsAt(Carbon::SUNDAY);
        $trnxs = Transaction::where(['stage' => $stageId, 'status' => 'approved'])->get();
        $last_week = Transaction::where(['stage' => $stageId])->whereNotIn('status', ['canceled', 'deleted'])->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();

        $tokens_sale = $users = $amount = 0;
        if ($trnxs) {
            $tokens_sale = $last_week->sum('total_tokens');
            $amount = $trnxs->sum('base_amount');
            $users = $trnxs->count('user');
        }

        $res['tokens'] = $tokens_sale;
        $res['amount'] = $amount;
        $res['users'] = $users;
        $res['trnxs'] = $trnxs;

        return (object) $res;
    }

    /**
     *
     * Check the stage transaction
     *
     * @version 1.1
     * @since 1.0
     * @return void
     */
    public static function token_add_to_account($trnx, $stage_action = '', $user_action = '')
    {
        $stage = IcoStage::where('id', $trnx->stage)->first();
        $user = User::where('id', $trnx->user)->first();
        if (!$stage) { return false; }
        if (!$user) { return false; }
        $tnx_tokens = (double) $trnx->total_tokens;
        $tnx_amount = (double) $trnx->base_amount;

        if ($stage_action == 'add' || $stage_action == 'sub') {
            if ($stage_action == 'add') {
                $stage->sales_token = number_format(((double) $stage->sales_token + $tnx_tokens), min_decimal(), '.', ''); 
                $stage->sales_amount = number_format(((double) $stage->sales_amount + $tnx_amount), max_decimal(), '.', '');
            } else {
                $stage->sales_token = number_format(((double) $stage->sales_token - $tnx_tokens), min_decimal(), '.', '');
                $stage->sales_amount = number_format(((double) $stage->sales_amount - $tnx_amount), max_decimal(), '.', ''); 
            }
            $stage->save();
            return true;
        }

        if ($user_action == 'add' || $user_action == 'sub') {
            if ($user_action == 'add') {
                $user->tokenBalance = number_format(((double) $user->tokenBalance + $tnx_tokens), min_decimal(), '.', ''); 
                $user->contributed = number_format(((double) $user->contributed + $tnx_amount), max_decimal(), '.', ''); 
            } else {
                $user->tokenBalance = number_format(((double) $user->tokenBalance - $tnx_tokens), min_decimal(), '.', '');
                $user->contributed = number_format(((double) $user->contributed - $tnx_amount), max_decimal(), '.', '');
            }
            $user->save();
            return true;
        }
        return false;
    }

    /**
     *
     * Check the stage transaction
     *
     * @version 1.0.1
     * @since 1.0
     * @return void
     */
    public static function token_adjust_to_stage($trnx, $token, $amount, $stage_action = '')
    {
        $stage = IcoStage::where('id', $trnx->stage)->first();
        if (!$stage) {
            return false;
        }
        $add_token = (double) $token;
        $add_amount = (double) $amount;

        if ($stage_action == 'add' || $stage_action == 'sub') {
            if ($stage_action == 'add') {
                $stage->sales_token = number_format(((double) $stage->sales_token + $add_token), min_decimal(), '.', '');
                $stage->sales_amount = number_format(((double) $stage->sales_amount + $add_amount), max_decimal(), '.', '');
            } else {
                $stage->sales_token = number_format(((double) $stage->sales_token - $add_token), min_decimal(), '.', '');
                $stage->sales_amount = number_format(((double) $stage->sales_amount - $add_amount), max_decimal(), '.', '');
            }
            $stage->save();
            return true;
        }
        return false;
    }

    /**
     *
     * Transaction in Sumation 
     *
     * @version 1.0
     * @since 1.1.2
     * @return void
     */
    public static function in_currency($all_tnx, $sum='amount')
    {
        $amounts = [];
        if(!empty($all_tnx) && $all_tnx->count() > 0){
            $currencies = $all_tnx->unique('currency')->pluck('currency');
            foreach ($currencies as $cur) {
                $amounts[$cur] = $all_tnx->where('currency', $cur)->sum($sum);
            }
        }
        return $amounts;
    }
}
