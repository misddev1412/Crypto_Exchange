<?php
/**
 * Calculate Bonus and Extra Data
 *
 * Calculate the Token Price, active bonus, active price rate etc.
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0
 */

namespace App\Helpers;

use App\Models\IcoMeta;
use App\Models\IcoStage;
use App\Models\Setting;

class TokenCalculate
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $stage;

    /**
     * Create a stage instance
     *
     */
    public function __construct()
    {
        if (get_setting('actived_stage') != '') {
            $this->stage = IcoStage::where('status', '!=', 'deleted')->where('id', get_setting('actived_stage'))->first();
            if (!$this->stage) {
                $this->stage = IcoStage::where('status', '!=', 'deleted')->orderBy('id', 'DESC')->first();
            }
        } else {
            $this->stage = IcoStage::where('status', '!=', 'deleted')->first();
        }
    }

    /**
     * Get the stage
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function get_stage()
    {
        return $this->stage;
    }

    /**
     * Get the bonus
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function get_bonuses($id)
    {
        $data = IcoMeta::get_data($id, 'bonus_option');

        $result = $data;

        return $result;
    }

    /**
     * Get the prices
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function get_prices()
    {
        $data = IcoMeta::get_data($this->stage->id, 'price_option');
        $result = $data;

        return $result;
    }

    /**
     * Get the current active bonus
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function get_current_bonus($attr, $id = '')
    {
        $id = ($id == NULL)? $this->stage->id : $id;
        $return = 0;
        $current_date = date('Y-m-d H:i:s');

        $data = $this->get_bonuses($id);
        $bonuses = $bonus_only = [];

        // Stage Fallback
        $ico_date_s = $this->stage->start_date;
        $ico_date_e = $this->stage->end_date;

        // Get based bonus
        $base = (object) [];
        if (!empty($data->base)) {
            $base->amount = $data->base->amount;
            $base->start_date = ($data->base->start_date == def_datetime('datetime_s')) ? $ico_date_s : $data->base->start_date;
            $base->end_date = ($data->base->end_date == def_datetime('datetime_e')) ? $ico_date_e : $data->base->end_date;
            $base->status = $data->base->status;
        }

        $bonus_tires = (object) [];
        $bonuses = $bonus_only = $active_tire = [];
        if (!empty($base)) {
            $bonus_tires->base = $base;
        }
        if (!empty($bonus_tires)) {
            foreach ($bonus_tires as $tire => $tire_opt) {
                if ($tire_opt->status == 1 && $tire_opt->amount >= 1 && $tire_opt->amount <= 100) {
                    // Override date-time if match fallback date
                    $tire_opt->start_date = ($tire_opt->start_date == def_datetime('datetime_s')) ? $ico_date_s : $tire_opt->start_date;
                    $tire_opt->end_date = ($tire_opt->end_date == def_datetime('datetime_e')) ? $ico_date_e : $tire_opt->end_date;

                    if ($current_date >= $tire_opt->start_date && $current_date <= $tire_opt->end_date) {
                        $bonuses[$tire] = $tire_opt;
                        $bonus_only[$tire] = $tire_opt->amount;
                        $active_tire[$tire] = $tire;
                    }
                }
            }
        }

        // Get Amount Bonus
        $bonus_amount = $data->bonus_amount;
        $bonuses_amount = $bonus_on_token = [];
        if (!empty($bonus_amount) && $bonus_amount->status == 1) {
            foreach ($bonus_amount as $tire => $tire_opt) {
                if ($tire != 'status' && $tire_opt->amount >= 1 && $tire_opt->token >= 1) {
                    $bonuses_amount[$tire] = $tire_opt;
                    $bonus_on_token[$tire_opt->token] = $tire_opt->amount;
                }
            }
        }

        $max_bonus = (!empty($bonus_only)) ? max(array_values($bonus_only)) : 0;
        $max_tire = '';

        foreach ($bonus_only as $t => $b) {
            if ($b == $max_bonus) {
                $max_tire = $t;
                break;
            }
        }

        $return = $max_bonus;

        if ($attr == 'active') {
            $return = (!empty($bonuses)) ? $bonuses[$max_tire] : null;
        }
        if ($attr == 'base') {
            $return = (!empty($base)) ? $base : 0;
        }
        if ($attr == 'amount') {
            ksort($bonus_on_token);
            $return = $bonus_on_token;/*(!empty($bonus_on_token)) ? $bonus_on_token : [0, 0]*/;
        }
        if ($attr == 'base-all') {
            $return = (!empty($bonuses)) ? $bonuses : 0;
        }
        if ($attr == 'amount-all') {
            $return = (!empty($bonuses_amount)) ? $bonuses_amount : [0, 0];
        }

        return $return;
    }

    /**
     * Get the current active price
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function get_current_price($attr = '')
    {
        $current_date = date('Y-m-d H:i:s');
        $return = 0;

        // Get Base Pricing
        $base = (object) [];
        $base->price = $this->stage->base_price;
        $base->min_purchase = $this->stage->min_purchase;
        $base->start_date = $this->stage->start_date;
        $base->end_date = $this->stage->end_date;
        $base->status = 1;

        // Stage Fallback
        $ico_date_s = $base->start_date;
        $ico_date_e = $base->end_date;
        $ico_min_token = $base->min_purchase;

        // Get Tier Pricing
        $data = $this->get_prices();
        $prices = $price_only = $minimum = [];
        if (!empty($base)) {
            $data->base = $base;
        }

        // Define Pricing
        if (!empty($data)) {
            foreach ($data as $tire => $tire_opt) {
                if ($tire_opt->status == 1 && $tire_opt->price > 0) {
                    // Override date-time if match fallback date
                    $tire_opt->start_date = ($tire_opt->start_date == def_datetime('datetime_s')) ? $ico_date_s : $tire_opt->start_date;
                    $tire_opt->end_date = ($tire_opt->end_date == def_datetime('datetime_e')) ? $ico_date_e : $tire_opt->end_date;
                    // Set min-purchase if matches up zero '0';
                    $tire_opt->min_purchase = ($tire_opt->min_purchase == 0) ? $ico_min_token : $tire_opt->min_purchase;

                    $prices[$tire] = $tire_opt;
                    $price_only[$tire] = $tire_opt->price;
                    $minimum[$tire] = $tire_opt->min_purchase;
                }
            }
        }
        asort($price_only);
        asort($prices);
        $lowest_price = (!empty($price_only)) ? min(array_values($price_only)) : 0;
        $min_tire = 'base';

        foreach ($price_only as $t => $p) {
            if ($p == $lowest_price) {
                $min_tire = $t;
                break;
            }
        }

        $return = $lowest_price;

        if ($attr == 'base') {
            $return = $base;
        }
        if ($attr == 'all') {
            $return = $prices;
        }
        if ($attr == 'price') {
            $return = $price_only;
        }
        if ($attr == 'min') {
            $return = $minimum[$min_tire];
        }
        if ($attr == 'minimum') {
            $return = $minimum;
        }

        return $return;
    }

    /**
     * Calculate the bonus
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function calc_bonus($token, $type = '', $output = 'total')
    {
        $amount = $bonus_percent = $amount_bonus_percent = 0;
        if (empty($token)) {
            return $amount;
        }

        $bonus_for_regular = $this->get_current_bonus(null);
        $bonus_for_amount = $this->get_current_bonus('amount');

        // Based Bonus
        $bonus_percent = ($bonus_for_regular) ? $bonus_for_regular : $bonus_percent;
        $bonus_regular = ($token * $bonus_percent) / 100;
        $bonus_regular = round($bonus_regular, min_decimal());

        // Amount Bonus
        if (!empty($bonus_for_amount)) {
            foreach ($bonus_for_amount as $k => $bn) {
                $amount_bonus_percent = ($token >= $k) ? $bn : $amount_bonus_percent;
            }
        }
        $bonus_amount = ($token * $amount_bonus_percent) / 100;
        $bonus_amount = round($bonus_amount, min_decimal());

        // Total Bonus Tokens
        $total_percent = $bonus_percent + $amount_bonus_percent;
        $total_bonus = round(($bonus_regular + $bonus_amount), min_decimal());

        // $return => @default->total
        $amount = ($output == 'percent') ? $total_percent : $total_bonus;
        if ($output == 'array') {
            $amount = array('based' => array('percent' => $bonus_percent, 'token' => $bonus_regular),
                'amount' => array('percent' => $amount_bonus_percent, 'token' => $bonus_amount),
                'total' => array('percent' => $total_percent, 'token' => $total_bonus));
        }

        // $return => @based
        if ($type == 'based') {
            $amount = ($output == 'percent') ? $bonus_percent : $bonus_regular;
            $amount = ($output == 'array') ? array('percent' => $bonus_percent, 'token' => $bonus_regular) : $amount;
        }

        // $return => @amount
        if ($type == 'amount') {
            $amount = ($output == 'percent') ? $amount_bonus_percent : $bonus_amount;
            $amount = ($output == 'array') ? array('percent' => $amount_bonus_percent, 'token' => $bonus_amount) : $amount;
        }

        return $amount;
    }

    /**
     * Calculate the token
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function calc_token($token, $output = 'total')
    {
        if (empty($token)) {
            return 0;
        }

        $return = 0;

        // Price Calculation
        $price = $this->get_current_price();
        $cost = [];
        $cost['base'] = $token * $price;
        foreach (Setting::active_currency() as $name => $rate) {
            $cost[$name] = $token * token_rate($price, $name);
        }
      
        // Bonus Calculation
        $token_bonus = $this->calc_bonus($token, 'all', 'total');
        $token_bonus_base = $this->calc_bonus($token, 'based', 'total');
        $token_bonus_amount = $this->calc_bonus($token, 'amount', 'total');
        $token_purchase = $token;
        $total_token = round(($token_purchase + $token_bonus), min_decimal());

        if ($output == 'total') {
            $return = $total_token;
        }
        if ($output == 'price') {
            $return = (object) $cost;
        }
        if ($output == 'bonus') {
            $return = $token_bonus;
        }
        if ($output == 'bonus-base') {
            $return = $token_bonus_base;
        }
        if ($output == 'bonus-token') {
            $return = $token_bonus_amount;
        }
        
        return $return;
    }

    /**
     * Calculate the price
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function calc_amount($get = array(), $receive_amount = null, $rate = null, $currency = 'usd', $output = 'token')
    {
        if (empty($get)) {
            return 0;
        }

        $return = $token = $tnx_data = 0;
        $amount = (isset($get['amount']) && $get['amount'] != '') ? $get['amount'] : 0;
        $tnx = (isset($get['tnx']) && $get['tnx'] != '') ? $get['tnx'] : false;

        if ($tnx) {
            $trnx = Transaction::find($tnx);
            if ($trnx->receive_amount != $trnx->amount) {
                if ($trnx->receive_currency == $trnx->currency) {
                    $token = ($trnx->receive_amount / $trnx->currency_rate);
                } else {
                    $rate_r = Setting::exchange_rate($trnx->base_currency_rate, $trnx->receive_currency);
                    $token = ($trnx->receive_amount / $rate_r);
                }
            } else {
                $token = (1 / $trnx->currency_rate) * $trnx->receive_amount;
            }
        } else {
            if ($rate == null) {
                $rate = $this->get_current_price($currency);
            }
            $token = (1 / $rate) * $amount;
        }

        $return = $token;
        return $return;
    }
}
