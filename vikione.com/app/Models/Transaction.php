<?php
/**
 * Transaction Model
 *
 *  Manage the Transactions
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.1.5
 */
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use DB; 

class Transaction extends Model
{

    /*
     * Table Name Specified
     */
    protected $table = 'transactions';

    protected $fillable = ['tnx_id', 'tnx_type', 'tnx_time', 'tokens', 'bonus_on_base', 'bonus_on_token', 'total_bonus', 'total_tokens', 'stage', 'user', 'amount', 'receive_amount', 'receive_currency', 'base_amount', 'base_currency', 'base_currency_rate', 'currency', 'currency_rate', 'all_currency_rate', 'wallet_address', 'payment_method', 'payment_id', 'payment_to', 'checked_by', 'added_by', 'checked_time', 'details', 'extra', 'status', 'dist', 'point', 'fee'
    ];

    /**
     *
     * Relation with user
     *
     * @version 1.0.1
     * @since 1.0
     * @return void
     */
    public function tnxUser()
    {
        return $this->belongsTo(User::class, 'user', 'id');
    }


    /**
     *
     * Relation with auth user
     *
     * @version 1.0.0
     * @since 1.1.2
     * @return void
     */
    public function user_tnx()
    {
        return $this->belongsTo(User::class, 'user', 'id')->where('user', auth()->id());
    }

    /**
     *
     * Relation with receiver
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'payment_to', 'email');
    }

    /**
     *
     * Relation with user by id
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function user($id)
    {
        return \App\Models\User::find($id);
    }

    /**
     *
     * Relation with stage
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function ico_stage()
    {
        return $this->belongsTo(IcoStage::class, 'stage', 'id');
    }
    
    /**
     *
     * Get transaction by on current user
     *
     * @version 1.0.0
     * @since 1.1.2
     * @return \Illuminate\Database\Eloquent
     */
    public static function get_by_own($where=null, $where_not=null) {
        // $return = (empty($where)) ? self::has('user_tnx') : self::has('user_tnx')->where($where);
        $by_user = self::has('user_tnx');
        if(!empty($where)) {
            $by_user->where($where);
        }
        if(!empty($where_not)) {
            $by_user->whereNotIn($where_not);
        }
        return $by_user;
    }
    
    /**
     *
     * Get transaction by user
     *
     * @version 1.0.0
     * @since 1.1.2
     * @return \Illuminate\Database\Eloquent
     */
    public static function by_user($user, $where=null, $where_not=null) {
        $by_user = self::where('user', $user);
        if(!empty($where)) {
            $by_user->where($where);
        }
        if(!empty($where_not)) {
            $by_user->whereNotIn($where_not);
        }
        return $by_user;
    }
    
    /**
     *
     * Get transaction by stage
     *
     * @version 1.0.0
     * @since 1.1.2
     * @return \Illuminate\Database\Eloquent
     */
    public static function by_stage($stage, $where=null, $where_not=null) {
        $by_stage = self::where('stage', $stage);
        if(!empty($where)) {
            $by_stage->where($where);
        }
        if(!empty($where_not)) {
            $by_stage->whereNotIn($where_not);
        }
        return $by_stage;
    }
    
    /**
     *
     * Get transaction by type
     *
     * @version 1.0.0
     * @since 1.1.2
     * @return \Illuminate\Database\Eloquent
     */
    public static function by_type($type, $where=null, $where_not=null) {
        $by_type = self::where('tnx_type', $type);
        if(!empty($where)) {
            $by_type->where($where);
        }
        if(!empty($where_not)) {
            $by_type->whereNotIn($where_not);
        }
        return $by_type;
    }
    
    /**
     *
     * Get transaction by type
     *
     * @version 1.0.0
     * @since 1.1.2
     * @return \Illuminate\Database\Eloquent
     */
    public static function by_status($status, $where=null, $where_not=null) {
        $by_status = self::where('status', $status);
        if(!empty($where)) {
            $by_status->where($where);
        }
        if(!empty($where_not)) {
            $by_status->whereNotIn($where_not);
        }
        return $by_status;
    }

    /**
     *
     * Advanced Filter Method
     *
     * @version 1.0.0
     * @since 1.1.0
     * @return void
     */
    public static function AdvancedFilter(Request $request) {
        if($request->s){
            $trnxs  = Transaction::whereNotIn('status', ['deleted', 'new'])->whereNotIn('tnx_type', ['withdraw'])
                        ->where(function($q) use ($request){
                            $id_num = (int)(str_replace(config('icoapp.tnx_prefix'), '', $request->s));
                            $q->orWhere('id', $id_num)->orWhere('tnx_id', 'like', '%'.$request->s.'%');
                        });
            return $trnxs;
        }
        if($request->filter){
            $deleted    = ($request->state=='deleted') ? 'blank' : 'deleted';

            $trnxs = Transaction::whereNotIn('status', [$deleted, 'new'])->whereNotIn('tnx_type', ['withdraw'])
                        ->where(self::keys_in_filter( $request->only(['type', 'state', 'stg', 'pmg', 'pmc']) ))
                        ->when($request->search, function($q) use ($request){
                            $is_user    = (isset($request->by) && $request->by=='usr') ? true : false;
                            $prefix     = ($is_user) ? config('icoapp.user_prefix') : config('icoapp.tnx_prefix');
                            $id_num     = (int)(str_replace($prefix, '', $request->search));
                            $where_in   = ($is_user) ? 'user' : 'id';
                            $q->where($where_in, $id_num);
                        })
                        ->when($request->date, function($q) use ($request){
                            $dates = self::date_in_filter($request);
                            $q->whereBetween('tnx_time', $dates);
                        });
            return $trnxs;
        }
    }

    /**
    * Search/Filter parametter exchnage with database value
    *
    * @version 1.0.0
    * @since 1.1.0
    * @return void
    */
    protected static function keys_in_filter($request) {
        $result = [];
        $find = ['type', 'state', 'stg', 'pmg', 'pmc'];
        $replace = ['tnx_type', 'status', 'stage', 'payment_method', 'currency'];
        foreach($request as $key => $values) {
            $set_key = str_replace($find, $replace, $key);
            $result[$set_key] = trim($values);

            if(empty($result[$set_key])) {
                unset($result[$set_key]);
            }
        }

        return $result;
    }

    /**
    * Date filter value set for search 
    *
    * @version 1.0.0
    * @since 1.1.0
    * @return void
    */
    protected static function date_in_filter($request) {
        $app_start = Setting::where('field', 'site_name')->value('created_at');
        $date = [$app_start, now()->toDateTimeString()];
        $get_date = $request->date;

        if($get_date == 'custom'){
            $from = $request->get('from', $app_start);
            $to = $request->get('to', date('m/d/Y'));
            $date = [
                _cdate($from)->toDateTimeString(),
                _cdate($to)->endOfDay()->toDateTimeString(),
            ];
        }
        if($get_date == 'today'){
            $today = Carbon::now()->today();
            $now = Carbon::now()->today()->endOfDay();
            $date = [
                $today,
                $now
            ];
        }

        if($get_date == '7day'){
            $first = new Carbon();
            $last = new Carbon();
            $date = [
                $first->subDays(7)->startOfDay(),
                $last->today()->subDay()->endOfDay()
            ];
        }

        if($get_date == '15day'){
            $first = new Carbon();
            $last = new Carbon();
            $date = [
                $first->subDays(15)->startOfDay(),
                $last->today()->subDay()->endOfDay()
            ];
        }

        if($get_date == '30day'){
            $first = new Carbon();
            $last = new Carbon();
            $date = [
                $first->subDays(30)->startOfDay(),
                $last->today()->subDay()->endOfDay()
            ];
        }
        
        if($get_date == '90day'){
            $first = new Carbon();
            $last = new Carbon();
            $date = [
                $first->subDays(90)->startOfDay(),
                $last->today()->subDay()->endOfDay()
            ];
        }

        if($get_date == 'this-month'){
            $first =  new Carbon();
            $last = new Carbon();
            $date = [
                $first->firstOfMonth()->startOfDay(),
                $last->lastOfMonth()->endOfDay()
            ];
        }

        if($get_date == 'last-month'){
            $first = new Carbon();
            $last = new Carbon();
            $date = [
                $first->firstOfMonth()->subMonths(1)->startOfDay(),
                $last->lastOfMonth()->subMonths(1)->endOfDay()
            ];
        }

        if($get_date == 'this-year'){
            $first = Carbon::now();
            $last = Carbon::now();
            $date = [
                $first->setDate($first->year, 1, 1)->startOfDay(),
                $last->setDate($last->year, 12, 31)->endOfDay()
            ];
        }

        if($get_date == 'last-year'){
            $first = Carbon::now();
            $last = Carbon::now();
            $date = [
                $first->setDate($first->year, 1, 1)->startOfDay()->subYears(1),
                $last->setDate($last->year, 12, 31)->endOfDay()->subYears(1)
            ];
        }

        return $date;
    }

    /**
     *
     * Dashboard data
     *
     * @version 1.4
     * @since 1.0
     * @return void
     */
    public static function dashboard($chart = 7) {
        $base_amount = 0; $max = max_decimal();
        $all_base = self::where(['status' => 'approved', 'tnx_type' => 'purchase', 'refund' => null])->get();
        foreach ($all_base as $item) {
            $base_amount += $item->base_amount;
        }

        $data['currency'] = (object) [
            'usd' => round(self::amount_count('USD')->total, $max),
            'eur' => round(self::amount_count('EUR')->total, $max),
            'gbp' => round(self::amount_count('GBP')->total, $max),
            'cad' => round(self::amount_count('CAD')->total, $max),
            'aud' => round(self::amount_count('AUD')->total, $max),
            'try' => round(self::amount_count('TRY')->total, $max),
            'rub' => round(self::amount_count('RUB')->total, $max),
            'inr' => round(self::amount_count('INR')->total, $max),
            'brl' => round(self::amount_count('BRL')->total, $max),
            'nzd' => round(self::amount_count('NZD')->total, $max),
            'pln' => round(self::amount_count('PLN')->total, $max),
            'jpy' => round(self::amount_count('JPY')->total, $max),
            'myr' => round(self::amount_count('MYR')->total, $max),
            'idr' => round(self::amount_count('IDR')->total, $max),
            'ngn' => round(self::amount_count('NGN')->total, $max),
            'eth' => round(self::amount_count('ETH')->total, $max),
            'ltc' => round(self::amount_count('LTC')->total, $max),
            'btc' => round(self::amount_count('BTC')->total, $max),
            'xrp' => round(self::amount_count('XRP')->total, $max),
            'xlm' => round(self::amount_count('XLM')->total, $max),
            'bch' => round(self::amount_count('BCH')->total, $max),
            'bnb' => round(self::amount_count('BNB')->total, $max),
            'trx' => round(self::amount_count('TRX')->total, $max),
            'usdt' => round(self::amount_count('USDT')->total, $max),
            'usdc' => round(self::amount_count('USDC')->total, $max),
            'dash' => round(self::amount_count('DASH')->total, $max),
            'waves' => round(self::amount_count('WAVES')->total, $max),
            'xmr' => round(self::amount_count('XMR')->total, $max),
            'base' => round($base_amount, $max)
        ];
        $data['chart'] = self::chart($chart);

        $data['all'] = self::whereNotIn('status', ['deleted', 'new'])->whereNotIn('tnx_type', ['withdraw'])->orderBy('created_at', 'DESC')->limit(4)->get();


        return (object) $data;
    }
    /**
     *
     * Count the amount
     *
     * @version 1.2
     * @since 1.0
     * @return void
     */
    public static function amount_count($currency='', $extra=null) {
        $data['total'] = $data['base'] = 0;
        $currency = strtolower($currency);

        if(!empty($extra)) {
            $all = self::where(['status'=>'approved', 'tnx_type'=>'purchase', 'refund'=>null, 'currency'=>$currency])->where($extra)->get();
        } else {
            $all = self::where(['status'=>'approved', 'tnx_type'=>'purchase', 'refund'=>null, 'currency'=>$currency])->get();
        }
        foreach ($all as $tnx) {
            $data['total'] += $tnx->amount;
            $data['base'] += $tnx->base_amount;
        }
        return (object) $data;
    }
    /**
     *
     * Chart data
     *
     * @version 1.1
     * @since 1.0
     * @return void
     */
    public static function chart($get = 6) {
        $cd = Carbon::now(); //->toDateTimeString();
        $lw = $cd->copy()->subDays($get);

        $cd = $cd->copy()->addDays(1);
        $df = $cd->diffInDays($lw);
        $transactions = self::where(['status'=>'approved', 'tnx_type'=>'purchase'])
            ->whereBetween('created_at', [$lw, $cd])
            ->orderBy('created_at', 'DESC')
            ->get();
        $data['days'] = null;
        $data['data'] = null;
        $data['data_alt'] = null;
        $data['days_alt'] = null;
        for ($i = 1; $i <= $df; $i++) {
            $tokens = 0;
            foreach ($transactions as $tnx) {
                $tnxDate = date('Y-m-d', strtotime($tnx->tnx_time));
                if ($lw->format('Y-m-d') == $tnxDate) {
                    $tokens += $tnx->total_tokens;
                } else {
                    $tokens += 0;
                }
            }
            $data['data'] .= $tokens . ",";
            $data['data_alt'][$i] = $tokens;
            $data['days_alt'][$i] = ($get > 27 ? $lw->format('d M Y') : $lw->format('d M'));
            $data['days'] .= '"' . $lw->format('d M') . '",';
            $lw->addDay();
        }
        return (object) $data;
    }

    /**
     *
     * User contribution
     *
     * @version 1.2
     * @since 1.0
     * @return void
     */
    public static function user_contribution()
    {
        $data = [];
        $curs = array_keys(PaymentMethod::Currency);
        $user_tnx = self::get_by_own(['status' => 'approved', 'tnx_type' => 'purchase', 'refund' => null])->get();
        foreach ($curs as $cur) {
            $data[$cur] = $user_tnx->where('currency', $cur)->sum('amount');
        }
        $data['base'] = $user_tnx->sum('base_amount');
        return (object) $data;
    }

    /**
     *
     * Transaction Overview
     *
     * @version 1.0
     * @since 1.1.2
     * @return void
     */
    public static function user_mytoken($type='balance')
    {
        if($type=='balance') {
            $user = auth()->user();
            $user_tnx = self::get_by_own(['status'=>'approved','refund'=>null])->whereNotIn('tnx_type', ['refund','withdraw','transfer'])->get();
            $user_wd = self::get_by_own(['tnx_type' => 'withdraw'])->get();
            $user_tf = self::get_by_own(['tnx_type' => 'transfer'])->get();
            
            $wd_pending = $user_wd->where('status', 'pending')->sum('total_tokens');
            $ts_pending = $user_tf->where('status', 'pending')->sum('total_tokens');

            $balance_sum = (object) [
                'current'   => $user->tokenBalance,
                'current_in_base' => token_price($user->tokenBalance, base_currency()),
                'total'         => $user_tnx->sum('total_tokens'),
                'purchased'     => $user_tnx->where('tnx_type', 'purchase')->sum('total_tokens'),
                'referral'      => $user_tnx->where('tnx_type', 'referral')->sum('total_tokens'),
                'bonuses'       => $user_tnx->where('tnx_type', 'bonus')->sum('total_tokens'),
                'contributed'   => $user_tnx->where('tnx_type', 'purchase')->sum('base_amount'),
                'contribute_in' => self::in_currency($user_tnx->where('tnx_type', 'purchase')),
                'has_withdraw'  => ($user_wd->count() > 0) ? true : false,
                'withdraw'      => $user_wd->where('status', 'approved')->sum('total_tokens'),
                'has_transfer'  => ($user_tf->count() > 0) ? true : false,
                'transfer'      => $user_tf->where('status', 'approved')->sum('tokens'),
                'pending'       => ($wd_pending + $ts_pending)
            ];
            return $balance_sum; 

        } elseif($type='stages') {
            $get_stages = IcoStage::with([ 'tnx_by_user'=> function($q) { $q->where(['refund' => null, 'status' => 'approved'])->whereNotIn('tnx_type', ['refund'])->has('user_tnx'); } ])->get();
            $stages_sum = [];
            if($get_stages->count() > 0) {
                foreach ($get_stages as $stage) {
                    if($stage->tnx_by_user->count() > 0) {
                        $stages_sum[] = (object) [
                            'stage' => $stage->id,
                            'name' => $stage->name,
                            'token' => $stage->tnx_by_user->sum('total_tokens'),
                            'purchase' => $stage->tnx_by_user->where('tnx_type', 'purchase')->sum('total_tokens'),
                            'bonus' => $stage->tnx_by_user->where('tnx_type', 'bonus')->sum('total_tokens'),
                            'referral' => $stage->tnx_by_user->where('tnx_type', 'referral')->sum('total_tokens'),
                            'contribute' => $stage->tnx_by_user->where('tnx_type', 'purchase')->sum('base_amount'),
                            'contribute_in' => self::in_currency($stage->tnx_by_user->where('tnx_type', 'purchase'))
                        ];
                    }
                }
            }
            return $stages_sum;
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

    /**
    *     _
    * .__(.)< (MEOW)
    * \___)
    * Author : Thinh Nguyen
    * Created at : 2020-11-06 10:55:59
    * Function todo : 
    */
    public function listTokenPending($userId, $limit = 3)
    {
        $sql = "SELECT ABS(ROUND(tokens)) as tokens, id, tnx_id
            FROM transactions 
            WHERE transactions.user_receive = $userId 
            AND transactions.status = 'pending' 
            ORDER BY transactions.created_at 
            DESC LIMIT $limit";

        $result = DB::select($sql);
        return $result;
    }
}
