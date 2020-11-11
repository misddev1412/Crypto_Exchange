<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\APIException;

class APIController extends Controller
{
    public function __construct()
    {
        if( $this->hasKey() === false && !app()->runningInConsole()){
            throw new APIException("Provide valid access key", 401);
        }
    }
    /**
     * Check the API key 
     */
    protected function hasKey() : bool
    {
        $api_key = request()->secret;
        return (get_setting('site_api_key', null) == $api_key);
    }

    /**
     * return the specified resource.
     */
    protected function bonus_data()
    {
        $stage = active_stage();
        $base = (get_base_bonus($stage->id)) ? get_base_bonus($stage->id) : 0;
        $amount = (get_base_bonus($stage->id, 'amount')) ? get_base_bonus($stage->id, 'amount') : 0;
        $base_dt = ($base > 0) ? get_base_bonus($stage->id, 'base') : [];
        
        $bonus_data = ['base' => $base];
        if ($base > 0) {
            $bonus_data['start'] = isset($base_dt->start_date) ? $base_dt->start_date : $stage->start_date;
            $bonus_data['end'] = isset($base_dt->end_date) ? $base_dt->end_date : $stage->end_date;
        }
        $bonus_data['amount'] = $amount;

        return $bonus_data;
    }
    /**
     * return the specified resource.
     */
    protected function stage_data($type='')
    {
        $stage = active_stage();
        $in_caps = (token('sales_cap')) ? token('sales_cap') : 'token';
        $in_total = (token('sales_total')) ? token('sales_total') : 'token';
        $in_raised = (token('sales_raised')) ? token('sales_raised') : 'token';

        $in_caps_cur = ($in_caps=='token') ? base_currency() : $in_caps;
        $in_total_cur = ($in_total=='token') ? base_currency() : $in_total;
        $in_raised_cur = ($in_raised=='token') ? base_currency() : $in_raised;

        $token = ($stage->total_tokens) ? $stage->total_tokens : 0;
        $token_cur = to_num_token($token). ' '.token_symbol();
        $token_amt = to_num(token_price($token, $in_total_cur), 'auto', ',') . ' ' . strtoupper($in_total_cur);

        $sold = ($stage->soldout) ? $stage->soldout : 0; //@v1.1.2 @old sales_token
        $sold_cur = to_num_token($sold). ' '.token_symbol();
        $sold_amt = to_num(token_price($sold, $in_raised_cur), 'auto', ',') . ' ' . strtoupper($in_raised_cur);

        $soft = ($stage->soft_cap) ? $stage->soft_cap : 0;
        $soft_amt = to_num(token_price($soft, $in_caps_cur), 'auto', ',') . ' ' . strtoupper($in_caps_cur);
        $hard = ($stage->hard_cap) ? $stage->hard_cap : 0;
        $hard_amt = to_num(token_price($hard, $in_caps_cur), 'auto', ',') . ' ' . strtoupper($in_caps_cur);

        $bonus_data = $this->bonus_data();

        $response_minimal = array(
            'ico' => active_stage_status($stage),
            'total' => $token_cur,
            'total_amount' => $token_amt,
            'sold' => $sold_cur,
            'sold_amount' => $sold_amt,
            'progress' => sale_percent($stage),
            'price' => current_price(),
            'start' => $stage->start_date,
            'end' => $stage->end_date,
            'min' => $stage->min_purchase,
            'max' => $stage->max_purchase,
            'soft' => $soft,
            'soft_amount' => $soft_amt,
            'hard' => $hard,
            'hard_amount' => $hard_amt,
        );
        
        $response_full = array(
            'ico' => active_stage_status($stage),
            'total' => $token_cur,
            'total_amount' => $token_amt,
            'total_token' => $token, 
            'sold' => $sold_cur,
            'sold_amount' => $sold_amt,
            'sold_token' => $sold, 
            'progress' => sale_percent($stage),
            'price' => current_price(),
            'bonus' => $bonus_data,
            'start' => $stage->start_date,
            'end' => $stage->end_date,
            'min' => $stage->min_purchase,
            'max' => $stage->max_purchase,
            'soft' => ['cap' => $soft, 'amount' => $soft_amt, 'percent' => round(ico_stage_progress('soft'), 2) ],
            'hard' => ['cap' => $hard, 'amount' => $hard_amt, 'percent' => round(ico_stage_progress('hard'), 2) ],
        );
        return ($type=='full') ? $response_full : $response_minimal;
    }

    /**
     * Display the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function stage()
    {
        $response = $this->stage_data();
        $data = [
            'success' => true,
            'response' => $response
        ];
        
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Display the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function stage_full()
    {
        $response = $this->stage_data('full');
        $data = [
            'success' => true,
            'response' => $response
        ];

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Display the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function bonuses()
    {
        
        $bonus_data = $this->bonus_data();

        $data = [
            'success' => true, 
            'response' => $bonus_data
        ];
        
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Display the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function prices()
    {
        $get_price = current_price('base');
        $prices_data = ['price' => $get_price->price, 'min' => $get_price->min_purchase, 'end' => $get_price->end_date];
        $data = [
            'success' => true, 
            'response' => $prices_data
        ];
        
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
}
