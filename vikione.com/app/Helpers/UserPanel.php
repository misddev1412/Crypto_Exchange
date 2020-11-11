<?php

/**
 * UserPanel Helper 
 *
 * This class for manage user panel data etc.
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0.5
 */

namespace App\Helpers;

use DB;
use App\Models\Transaction;
use Auth;

/**
 * UserPanel Class
 */
class UserPanel
{

    /**
     * user_info()
     *
     * @version 1.3
     * @since 1.0
     * @return void
     */
    public static function user_info($data = null, $atttr = '')
    {
        $user = (empty($data)) ? auth()->user() : $data;
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $return = '<div' . $g_id . ' class="user-dropdown-head' . $g_cls . '">
        <h6 class="user-dropdown-name">' . $user->name . '<span>(' . set_id($user->id) . ')</span></h6>
        <span class="user-dropdown-email">' . $user->email . '</span>
        </div>

        <div class="user-status">
        <h6 class="user-status-title">' . __('Token Balance') . '</h6>
        <div class="user-status-balance">' . to_num_token($user->tokenBalance) . ' <small>' . token('symbol') . '</small></div>
        </div>';

        return $return;
    }

    /**
     * user_balance()
     *
     * @version 1.3
     * @since 1.0
     * @return void
     */
    public static function user_balance($data = null, $atttr = '')
    {
        $user = (empty($data)) ? auth()->user() : $data;
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $return = '<div' . $g_id . ' class="user-status' . $g_cls . '">
        <h6 class="text-white">' . $user->email . ' <small class="text-white-50">(' . set_id($user->id) . ')</small></h6>
        <h6 class="user-status-title">' . __('Token Balance') . '</h6>
        <div class="user-status-balance">' . to_num_token($user->tokenBalance) . ' <small>' . token('symbol') . '</small></div>
        </div>';

        return $return;
    }

    /**
     * user_balance_card()
     *
     * @version 1.3.1
     * @since 1.0
     * @return void
     */
    public static function user_balance_card($data = null, $atttr = '')
    {
        $user           = auth()->user();
        $transaction    = new Transaction;
        $listPending    = $transaction->listTokenPending($user->id);
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $ver_cls = ($vers == 'side') ? ' token-balance-with-icon' : '';
        $ver_icon = ($vers == 'side') ? '<div class="token-balance-icon"><img src="' . asset('images/token-symbol-light.png') . '" alt=""></div>' : '';

        $base_cur = base_currency();
        $base_con = isset($data->$base_cur) ? to_num($data->$base_cur, 'auto')  : 0;
        $base_out = '<li class="token-balance-sub"><span class="lead">' . ($base_con > 0 ? $base_con : '~') . '</span><span class="sub">' . strtoupper($base_cur) . '</span></li>';

        $cur1_out = $cur2_out = '';
        if (gws('user_in_cur1', 'eth') != 'hide') {
            $cur1 = gws('user_in_cur1', 'eth');
            $cur1_con = (gws('pmc_active_' . $cur1) == 1) ? to_num($data->$cur1, 'auto') : 0;
            $cur1_out = ($cur1 != $base_cur) ? '<li class="token-balance-sub"><span class="lead">' . ($cur1_con > 0 ? $cur1_con : '~') . '</span><span class="sub">' . strtoupper($cur1) . '</span></li>' : '';
        }

        if (gws('user_in_cur2', 'btc') != 'hide') {
            $cur2 = gws('user_in_cur2', 'btc');
            $cur2_con = (gws('pmc_active_' . $cur2) == 1) ? to_num($data->$cur2, 'auto') : 0;
            $cur2_out = ($cur2 != $base_cur) ? '<li class="token-balance-sub"><span class="lead">' . ($cur2_con > 0 ? $cur2_con : '~') . '</span><span class="sub">' . strtoupper($cur2) . '</span></li>' : '';
        }

        $contribute = ($base_out || $cur1_out || $cur2_out) ? '<div class="token-balance token-balance-s2"><h6 class="card-sub-title">' . __('Your Contribution in') . '</h6><ul class="token-balance-list">' . $base_out . $cur1_out . $cur2_out . '</ul></div>' : '';
        $itemTransPending   = '';
        $i = 0;
        foreach ($listPending as $item) {
            $i++;
            $itemTransPending .=  '
            <tr class="row-pending">
                <td class="text-center">' . number_format($item->tokens) . '</td>
                <td class="text-center"><a onclick="formPaymentPending(\'' . $item->tnx_id . '\')" ><i class="ti ti-credit-card"></i> </a></td>
            </tr>';
                    
        }
        $return = '<div' . $g_id . ' class="token-statistics card card-token token-balance-original-container' . $g_cls . '">
            <div class="card-innr"><div class="token-balance' . $ver_cls . '">' . $ver_icon . '
            <div class="token-balance-text text-center"><h6 class="card-sub-title">' . __('Token Balance') . '</h6>
            <span class="lead text-danger font-weight-bold" style="font-size:1.7em">' . to_num_token($user->tokenBalance) . ' <span>' . token('symbol') . ' <em class="fas fa-info-circle fs-11" data-toggle="tooltip" data-placement="right" title="' . __('Equivalent to') . ' ' . token_price($user->tokenBalance, base_currency()) . ' ' . base_currency(true) . '"></em></span></span>
            </div>
            <div class="gaps-1-5x"></div>
            </div>' . $contribute . '
            <div class="token-balance-pending-container">
            <table class="table text-white table-hover">
                <thead>
                    <tr>
                   
                        <th class="text-center">
                            Token deposit pending
                        </th>
                        <th class="text-center">
                            Payment
                        </th>
                    </tr>
                </thead>
                <tbody>
                    ' . $itemTransPending . '
                </tbody>
            </table>
        </div>
            </div>
                
            </div>';

        return $return;
    }

    /**
     * user_token_block()
     *
     * @version 1.2
     * @since 1.0
     * @return void
     */
    public static function user_token_block($data = '', $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';
        $_CUR = base_currency(true);
        $_SYM = token_symbol();
        $base_currency = base_currency();
        $token_1price = token_calc(1, 'price')->$base_currency;
        $token_1rate = token_rate(1, token('default_in_userpanel', 'eth'));
        $token_ratec = token('default_in_userpanel', 'ETH');

        $card1 = '<div class="token-info text-center">
        <img class="token-info-icon" src="' . asset('images/token-symbol.png') . '" alt="">
        <div class="gaps-2x"></div>
        <h3 class="token-info-head text-light">1 ' . $_SYM . ' = ' . to_num($token_1price, 'max', ',', true) . ' ' . $_CUR . '
        </h3>
        <h5 class="token-info-sub">1 ' . $_CUR . ' = ' . to_num($token_1rate, 'max', ',', true) . ' ' . $token_ratec . '</h5>
        </div>';
        $card2 = '<div class="token-info bdr-tl">
        <div>
        <ul class="token-info-list">
        <li><span>' . __('Token Name') . ':</span>' . token('name') . '</li>
        <li><span>' . __('Token Symbol') . ':</span>' . $_SYM . '</li>
        </ul>';
        $card2 .= (get_setting('site_white_paper') != '' ? '<a href="' . route('public.white.paper') . '" target="_blank" class="btn btn-primary"><em class="fas fa-download mr-3"></em>' . __('Download Whitepaper') . '</a>' : '');
        $card2 .= '</div>
        </div>';

        $return = '';
        $status = ucfirst(active_stage_status());
        if ($vers == 'buy') {
            $return .= '<div class="card card-full-height"><div class="card-innr">';
            $return .= '<h6 class="card-title card-title-sm">' . active_stage()->name . '<span class="badge badge-success ucap">' . __($status) . '</span></h6>';
            $return .= '<h3 class="text-dark">1 ' . $_SYM . ' = ' . to_num($token_1price, 'max', ',', true) . ' ' . $_CUR . ' <span class="d-block text-exlight ucap fs-12">1 ' . $_CUR . ' = ' . to_num($token_1rate, 'max', ',', true) . ' ' . $token_ratec . '</span></h3>';
            $return .= '<div class="gaps-0-5x"></div><div class="d-flex align-items-center justify-content-between mb-0"><a href="' . route('user.token') . '" class="btn btn-md btn-primary">' . __('Buy Token Now') . '</a></div>';

            $return .= '</div></div>';
        } else {
            $return .= '<div' . $g_id . ' class="token-information card card-full-height' . $g_cls . '">';
            if ($vers == 'prices') {
                $return .= $card1;
            } elseif ($vers == 'info') {
                $return .= $card2;
            } else {
                $return .= '<div class="row no-gutters height-100">
                <div class="col-md-6">' . $card1 . '</div>
                <div class="col-md-6">' . $card2 . '</div>
                </div>';
            }
            $return .= '</div>';
        }

        return $return;
    }

    /**
     * add_wallet_alert()
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public static function add_wallet_alert()
    {
        return '<a href="javascript:void(0)" class="btn btn-danger btn-xl btn-between w-100 mgb-1-5x user-wallet">' . __('Add your wallet address before buy') . ' <em class="ti ti-arrow-right"></em></a>
        <div class="gaps-1x mgb-0-5x d-lg-none d-none d-sm-block"></div>';
    }

    /**
     * user_account_status()
     *
     * @version 1.1
     * @since 1.0
     * @return void
     */
    public static function user_account_status($data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $user = auth()->user();
        $heading = '<h6 class="card-title card-title-sm">' . __('Your Account Status') . '</h6><div class="gaps-1-5x"></div>';
        $email_status = $kyc_staus = '';
        if ($user->email_verified_at == null) {
            $email_status = '<li><a href="' . route('verify.resend') . '" class="btn btn-xs btn-auto btn-info">' . __('Resend Email') . '</a></li>';
        } else {
            $email_status = '<li><a href="javascript:void(0)" class="btn btn-xs btn-auto btn-success">' . __('Email Verified') . '</a></li>';
        }
        if (!is_kyc_hide()) {
            if (isset($user->kyc_info->status) && $user->kyc_info->status == 'approved') {
                $kyc_staus = '<li><a href="javascript:void(0)" class="btn btn-xs btn-auto btn-success">' . __('KYC Approved') . '</a></li>';
            } elseif (isset($user->kyc_info->status) && $user->kyc_info->status == 'pending') {
                $kyc_staus = '<li><a href="' . route('user.kyc') . '" class="btn btn-xs btn-auto btn-warning">' . __('KYC Pending') . '</a></li>';
            } else {
                $kyc_staus = '<li><a href="' . route('user.kyc') . '" class="btn btn-xs btn-auto btn-info"><span>' . __('Submit KYC') . '</span></a></li>';
            }
        }
        $return = ($email_status || $kyc_staus) ? '<div' . $g_id . ' class="user-account-status' . $g_cls . '">' . $heading . '<ul class="btn-grp">' . $email_status . $kyc_staus . '</ul></div>' : '';
        return $return;
    }


    /**
     * user_account_wallet()
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public static function user_account_wallet($data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $user = auth()->user();
        $title_cls = ' card-title-sm';
        $btn_cls = ' link link-ucap';

        $uwallet = '<h6 class="card-title' . $title_cls . '">' . __('Receiving Wallet') . '</h6><div class="gaps-1x"></div>';
        $uwallet .= '<div class="d-flex justify-content-between">';
        if ($user->walletAddress) {
            $uwallet .= '<span>' . show_str($user->walletAddress, 8) . ' ';
            if ($user->wallet() == 'pending') {
                $uwallet .= ' <em title="' . __('New address under review for approve.') . '" data-toggle="tooltip" class="fas fa-info-circle text-warning"></em></span>';
            }
        } else {
            $uwallet .= __('Add Your Wallet Address');
        }
        $uwallet .= '<a href="javascript:void(0)" data-toggle="modal" data-target="#edit-wallet" class="user-wallet' . $btn_cls . '">' . ($user->walletAddress != null ? __('Edit') : __('Add')) . '</a></div>';
        $support_token_wallet = (empty(token_wallet()) ? false : true);
        $return = ($uwallet && $support_token_wallet) ? '<div' . $g_id . ' class="user-receive-wallet' . $g_cls . '">' . $uwallet . '</div>' : '';
        return $return;
    }

    /**
     * user_kyc_info()
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public static function user_kyc_info($data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $user = auth()->user();
        $title_cls = ' card-title-sm';

        $heading = '<h6 class="card-title' . $title_cls . '">' . __('Identity Verification - KYC') . '</h6>';
        $ukyc = $heading . '<p>' . __('To comply with regulation, participant will have to go through identity verification.') . '</p>';
        if (!isset($user->kyc_info->status)) {
            $ukyc .= '<p class="lead text-light pdb-0-5x">' . __('You have not submitted your documents to verify your identity (KYC).') . '</p><a href="' . route('user.kyc.application') . '" class="btn btn-sm m-2 btn-icon btn-primary">' . __('Click to Proceed') . '</a>';
        }
        if (isset($user->kyc_info->status) && $user->kyc_info->status == 'pending') {
            $ukyc .= '<p class="lead text-info pdb-0-5x">' . __('We have received your document.') . '</p><p class="small">' . __('We will review your information and if all is in order will approve your identity. You will be notified by email once we verified your identity (KYC).') . '</p>';
        }
        if (isset($user->kyc_info->status) && ($user->kyc_info->status == 'rejected' || $user->kyc_info->status == 'missing')) {
            $ukyc .= '<p class="lead text-danger pdb-0-5x">' . __('KYC Application has been rejected!') . '</p><p>' . __('We were having difficulties verifying your identity. In our verification process, we found information are incorrect or missing. Please re-submit the application again and verify your identity.') . '</p><a href="' . route('user.kyc.application') . '?state=resubmit" class="btn btn-sm m-2 btn-icon btn-primary">' . __('Resubmit') . '</a><a href="' . route('user.kyc.application.view') . '" class="btn btn-sm m-2 btn-icon btn-secondary">' . __('View KYC') . '</a>';
        }
        if (isset($user->kyc_info->status) && $user->kyc_info->status == 'approved') {
            $ukyc .= '<p class="lead text-success pdb-0-5x"><strong>' . __('Identity (KYC) has been verified.') . '</strong></p><p>' . __('One for our team verified your identity. You are eligible to participate in our token sale.') . '</p><a href="' . route('user.token') . '" class="btn btn-sm m-2 btn-icon btn-primary">' . __('Purchase Token') . '</a><a href="' . route('user.kyc.application.view') . '" class="btn btn-sm m-2 btn-icon btn-success">' . __('View KYC') . '</a>';
        }
        if (token('before_kyc') == '1') {
            $ukyc .= '<h6 class="kyc-alert text-danger">* ' . __('KYC verification required for purchase token') . '</h6>';
        }

        $return = ($ukyc) ? '<div' . $g_id . ' class="kyc-info card' . $g_cls . '"><div class="card-innr">' . $ukyc . '</div></div>' : '';
        return $return;
    }

    /**
     * user_logout_link()
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public static function user_logout_link($data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $return = '<ul' . $g_id . ' class="user-links bg-light' . $g_cls . '">
        <li><a href="' . route('log-out') . '" onclick="event.preventDefault();document.getElementById(\'logout-form\').submit();"><i class="ti ti-power-off"></i>' . __('Logout') . '</a></li>
        </ul>
        <form id="logout-form" action="' . route('logout') . '" method="POST" style="display: none;"> <input type="hidden" name="_token" value="' . csrf_token() . '"> </form>';

        return $return;
    }

    /**
     * user_menu_links()
     *
     * @version 1.2
     * @since 1.0
     * @return void
     */
    public static function user_menu_links($data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        // v1.0.3 > v1.1.1
        $referral_link = (get_page('referral', 'status') == 'active' && is_active_referral_system()) ? '<li><a href="' . route('user.referral') . '"><i class="ti ti-infinite"></i>' . get_page('referral', 'menu_title') . '</a></li>' : '';
        // v1.1.2
        $withdraw_link = (nio_module()->has('Withdraw') && has_route('withdraw:user.index')) ? '<li><a href="' . route('withdraw:user.index') . '"><i class="ti ti-wallet"></i>' . __('Withdraw') . '</a></li>' : '';
        $return = '<ul' . $g_id . ' class="user-links' . $g_cls . '"><li><a href="' . route('user.account') . '"><i class="ti ti-id-badge"></i>' . __('My Profile') . '</a></li>' . $withdraw_link . $referral_link;
        $return .= '<li><a href="' . route('user.account.activity') . '"><i class="ti ti-eye"></i>' . __('Activity') . '</a></li>';
        $return .= '</ul>';

        return $return;
    }

    /**
     * kyc_footer_info()
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public static function kyc_footer_info($data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $email = (get_setting('site_support_email', get_setting('site_email'))) ? ' <a href="mailto:' . get_setting('site_support_email', get_setting('site_email')) . '">' . get_setting('site_support_email', get_setting('site_email')) . '</a>' : '';
        $gaps = '<div class="gaps-3x d-none d-sm-block"></div>';

        $return = ($email) ? '<p class="text-light text-center">' . (__('Contact our support team via email')) . ' - ' . $email . '</p><div class="gaps-1x"></div>' . $gaps : '';

        return $return;
    }

    /**
     * language_switcher()
     *
     * @version 1.0.1
     * @since 1.0.2
     * @return string
     */

    public static function language_switcher()
    {
        $l = str_replace('_', '-', app()->getLocale());

        $text = '<div class="lang-switch relative"><a href="javascript:void(0)" class="lang-switch-btn toggle-tigger">' . strtoupper($l) . '<em class="ti ti-angle-up"></em></a>';
        $text .= '<div class="toggle-class dropdown-content dropdown-content-up"><ul class="lang-list">';
        foreach (config('icoapp.supported_languages') as $lng) {
            $text .= '<li><a href="' . route('language') . '?lang=' . $lng . '">' . get_lang($lng) . '</a></li>';
        }
        $text .= '</ul></div></div>';
        return (is_lang_switch()) ? $text : '';
    }

    /**
     * social_links()
     *
     * @version 1.0.2
     * @since 1.0
     * @return void
     */
    public static function social_links($data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $link = json_decode(get_setting('site_social_links'));

        $fb = (isset($link->facebook) && $link->facebook != null) ? '<li><a href="' . $link->facebook . '"><em class="fab fa-facebook-f"></em></a></li>' : '';
        $tw = (isset($link->twitter) && $link->twitter != null) ? '<li><a href="' . $link->twitter . '""><em class="fab fa-twitter"></em></a></li>' : '';
        $in = (isset($link->linkedin) && $link->linkedin != null) ? '<li><a href="' . $link->linkedin . '"><em class="fab fa-linkedin-in"></em></a></li>' : '';
        $gh = (isset($link->github) && $link->github != null) ? '<li><a href="' . $link->github . '"><em class="fab fa-github-alt"></em></a></li>' : '';

        $yt = (isset($link->youtube) && $link->youtube != null) ? '<li><a href="' . $link->youtube . '"><em class="fab fa-youtube"></em></a></li>' : '';
        $md = (isset($link->medium) && $link->medium != null) ? '<li><a href="' . $link->medium . '"><em class="fab fa-medium-m"></em></a></li>' : '';
        $tg = (isset($link->telegram) && $link->telegram != null) ? '<li><a href="' . $link->telegram . '"><em class="fab fa-telegram-plane"></em></a></li>' : '';

        $social_exist = ($fb || $tw || $in || $gh || $yt || $md || $tg) ? true : false;
        $return = ($social_exist) ? '<ul' . $g_id . ' class="socials' . $g_cls . '">' . $fb . $tw . $in . $gh .  $yt . $md . $tg . '</ul>' : '';

        return ($data == 'exists') ? $social_exist : $return;
    }

    /**
     * footer_links()
     *
     * @version 1.0.2
     * @since 1.0
     * @return void
     */
    public static function footer_links($data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $how_to = (get_page('how_buy', 'status') == 'active') ? '<li><a href="' . route('public.pages', get_slug('how_buy')) . '">' . get_page('how_buy', 'menu_title') . '</a></li>' : '';
        $cs_page = (get_page('custom_page', 'status') == 'active') ? '<li><a href="' . route('public.pages', get_slug('custom_page')) . '">' . get_page('custom_page', 'menu_title') . '</a></li>' : '';
        $faqs = (get_page('faq', 'status') == 'active') ? '<li><a href="' . route('public.pages', get_slug('faq')) . '">' . get_page('faq', 'menu_title') . '</a></li>' : '';
        if (!auth()->check() || is_2fa_lock()) {
            $how_to = $faqs = $cs_page = '';
        }
        $privacy = (get_page('privacy', 'status') == 'active') ? '<li><a href="' . route('public.pages', get_slug('privacy')) . '">' . get_page('privacy', 'menu_title') . '</a></li>' : '';
        $terms = (get_page('terms', 'status') == 'active') ? '<li><a href="' . route('public.pages', get_slug('terms')) . '">' . get_page('terms', 'menu_title') . '</a></li>' : '';

        $is_copyright = ((isset($data['copyright']) && $data['copyright'] == true) || $vers == 'copyright') ? true : false;
        $copyrights = ($is_copyright) ? '<li>' . site_copyrights() . '</li>' : '';

        $is_lang = ((isset($data['lang']) && $data['lang'] == true) && is_lang_switch()) ? true : false;
        $lang = ($is_lang) ? '<li>' . Userpanel::language_switcher() . '</li>' : '';

        $return = ($privacy || $terms) ? '<ul' . $g_id . ' class="footer-links' . $g_cls . '">' . $cs_page . $how_to . $faqs . $privacy . $terms . $copyrights . $lang . '</ul>' : '';

        return (!is_maintenance() ? $return : '');
    }

    /**
     * copyrights()
     *
     * @version 1.0.1
     * @since 1.0.2
     * @return void
     */
    public static function copyrights($data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $copyrights = ($data == 'div') ? '<div' . $g_id . ' class="copyright-text' . $g_cls . '">' . site_copyrights() . '</div>' : site_copyrights();

        $return = $copyrights;

        return $return;
    }

    /**
     * content_block()
     *
     * @version 1.1
     * @since 1.0
     * @return void
     */
    public static function content_block($data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $return = '';
        $img_url = (isset($image) && $image != '') ? asset('images/' . $image) : '';
        if ($data == 'welcome') {
            $return .= '<div' . $g_id . ' class="card content-welcome-block' . $g_cls . '"><div class="card-innr">';
            $return .= ($img_url) ? '<div class="row guttar-vr-20px">' : '';

            if ($img_url) {
                $return .= '<div class="col-sm-5 col-md-4"><div class="card-image card-image-sm"><img width="240" src="' . $img_url . '" alt=""></div></div><div class="col-sm-7 col-md-8">';
            }
            $return .= '<div class="card-content">';
            $return .= '<h4>' . get_page('home_top', 'title') . '</h4>';
            $return .= get_page('home_top', 'description');
            $return .= '</div>';

            $return .= ($img_url) ? '</div></div>' : '';
            $return .= '<div class="d-block d-md-none gaps-0-5x mb-0"></div></div></div>';
        }

        if ($data == 'bottom') {
            $return = '<div' . $g_id . ' class="content-bottom-block card' . $g_cls . '"><div class="card-innr"><div class="table-responsive">' . get_page('home_bottom', 'description') . '</div></div></div>';
        }

        return $return;
    }

    /**
     * token_sales_progress()
     *
     * @version 1.2
     * @since 1.0
     * @return void
     */
    public static function token_sales_progress($data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $sales_raised = (token('sales_raised')) ? token('sales_raised') : 'token';
        $sales_total = (token('sales_total')) ? token('sales_total') : 'token';
        $sales_caps = (token('sales_cap')) ? token('sales_cap') : 'token';
        $title = $progress = $progress_bar = $sales_end_in = $sales_start_in = '';

        $title .= '<div class="card-head"><h5 class="card-title card-title-sm">' . __('Token Sales Progress') . '</h5></div>';

        $progress .= '<ul class="progress-info"><li><span>' . __('Raised Amount') . ' <br></span>' . ico_stage_progress('raised', $sales_raised) . '</li><li><span>' . __('Total Token') . ' <br></span>' . ico_stage_progress('total', $sales_total) . '</li></ul>';

        $no_class = ((active_stage()->hard_cap < 10) && (active_stage()->soft_cap < 10)) ? ' no-had-soft' : '';

        $progress_bar = '<div class="progress-bar' . $no_class . '">';
        if (active_stage()->hard_cap >= 10) {
            $progress_bar .= '<div class="progress-hcap" data-percent="' . ico_stage_progress('hard') . '"><div>' . __('Hard Cap') . ' <span>' . ico_stage_progress('hardtoken', $sales_caps) . '</span></div></div>';
        }
        if (active_stage()->soft_cap >= 10) {
            $progress_bar .= '<div class="progress-scap" data-percent="' . ico_stage_progress('soft') . '"><div>' . __('Soft Cap') . ' <span>' . ico_stage_progress('softtoken', $sales_caps) . '</span></div></div>';
        }
        $progress_bar .= '<div class="progress-percent" data-percent = "' . sale_percent(active_stage()) . '"></div></div>';

        $sales_end_in .= '<span class="card-sub-title ucap mgb-0-5x">' . __('Sales End in') . '</span><div class="countdown-clock" data-date="' . _date(active_stage()->end_date, 'Y/m/d H:i:s') . '"></div>';
        $sales_start_in .= '<span class="card-sub-title ucap mgb-0-5x">' . __('Sales Start in') . '</span><div class="countdown-clock" data-date="' . _date(active_stage()->start_date, 'Y/m/d H:i:s') . '"></div>';
        $sales_state = (is_upcoming() ? $sales_start_in : $sales_end_in);

        // If expaired or Completed
        if (is_expired() || is_completed()) {
            $sales_state = '<div class="gaps-2x"></div><h4 class="text-light text-center">' . __('Our token sales has been finished. Thank you very much for your contribution.') . '</h4>';
        }

        $return = '<div' . $g_id . ' class="card card-sales-progress' . $g_cls . '"><div class="card-innr">' . $title . $progress . $progress_bar . $sales_state . '</div></div>';

        return $return;
    }

    /**
     * user_referral_info()
     *
     * @version 1.0.0
     * @since 1.0.3
     * @return void
     */
    public static function user_referral_info($data = null, $atttr = '')
    {
        $atttr_def = array('id'    => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $auth       = auth();
        $refers = $heading = '';
        $ref_url    = route('public.referral') . '?ref=' . set_id($auth->id());
        $ref_page   = route('user.referral', get_slug('referral'));
        $more       = (isset($data['more']) && $data['more'] == 'hide') ? '' : '<div class="card-opt"><a href="' . $ref_page . '" class="link ucap">' . __('More') . '<em class="fas fa-angle-right ml-1"></em></a></div>';
        $heading    .= '<div class="card-head has-aside"><h6 class="card-title card-title-sm">' . __('Earn with Referral') . '</h6>' . $more . '</div>';
        $refers     .= '<p class="pdb-0-5x"><strong>' . __('Invite your friends & family.') . '</strong></p>';
        $refers     .= '<div class="copy-wrap mgb-0-5x"><span class="copy-feedback"></span><em class="copy-icon fas fa-link"></em><input type="text" class="copy-address" value="' . $ref_url . '" disabled /><button class="copy-trigger copy-clipboard" data-clipboard-text="' . $ref_url . '"><em class="ti ti-files"></em></button></div>';

        $return = ($refers) ? '<div' . $g_id . ' class="referral-info card' . $g_cls . '"><div class="card-innr">' . $heading . $refers . '</div></div>' : '';
        return (get_page('referral', 'status') == 'active' ? $return : '');
    }

    /**
     * user_account_status()
     *
     * @version 1.1
     * @since 1.0
     * @return void
     */
    public static function user_account_point_status($data = null, $atttr = '')
    {
        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $user = auth()->user();
        $percent = 0;
        $point = 0;

        $poinMultiples = json_decode($user->poinMultiply, true) ?? [];
        foreach ($poinMultiples as $value) {
            $point = $value['multiply'];
            $percent = $value['receiving'];
        }

        $heading = '<h6 class="card-sub-title">' . __('Your Point') . '</h6>';

        $return = '<div' . $g_id . ' class="user-account-status' . $g_cls . '">' . $heading;
        $return .= '<div class="gaps-1-2x"></div><h3>' . (round($user->tokenPoint,2) ?? 0) .'</h3>';
        $return .= '<div class="gaps-1-3x"></div><h6 class="card-sub-title">' . __('Affiliate has received') . '</h6>';
        $return .= '<div class="gaps-1-2x"></div><h5>' . (round($user->affiliate_point,2) ?? 0) .'</h5>';
        if ($point) {
            $return .= '<span class="d-block text-warning ucap fs-12">1 TOKEN = ' . $point . ' POINT</span>';
        }
        $return .= '<button id="bridge" class="btn btn-md btn-info" data-loading="' . __('Spinning bridge ...') . '" data-text="' . __('Get Point Now') . '" style="background: none;
    border: none;
    position: absolute;
    right: 0;
    top: 1px;"><img src="' . asset('images/one.jpeg') . '" alt="" style="width: 65%;
    float: right;
    top: 5px;
    position: absolute;
    right: 20px;"/></button> <div class="d-none spinner-grow text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>';
        $return .= '</h3>';    
        $return .= '<div class="user-information">
            <div class="user-information-row mt-2">
                <label>Your ID: </label> <span> ' . set_id($user->id) . ' </span>
            </div>
            <div class="user-information-row">
                <label>Your Email: </label> <span> ' . $user->email . ' </span>
            </div>
            <div class="user-information-row">
                <label>You′re with us: </label> <span> ' . Auth::user()->created_at->diffForHumans() . ' </span>
            </div>
            <div class="user-information-row">
                <div class="referral-form">
                <label>Your referral link: </label>
                <div class="copy-wrap mgb-1-5x">
                    <span class="copy-feedback"></span>
                    <em class="copy-icon fas fa-link"></em>
                    <input type="text" class="copy-address"
                        value="'. route('public.referral').'?ref='.set_id(auth()->id()) .'"
                        disabled>
                    <button class="copy-trigger copy-clipboard"
                        data-clipboard-text="'. route('public.referral').'?ref='.set_id(auth()->id()) .'"><em
                            class="ti ti-files"></em></button>
                </div>
               
            </div>
            </div>
        </div>';  
        $return .= '</div>';
        return $return;
    }

    /**
     * user_balance_card()
     *
     * @version 1.3.1
     * @since 1.0
     * @return void
     */
    public static function user_balance2_card($data = null, $atttr = '')
    {
        $user = auth()->user();
        $percent = 0;
        
        $poinMultiples = json_decode($user->poinMultiply, true) ?? [];
        foreach ($poinMultiples as $value) {
           $percent = $value['receiving'];
        }

        $atttr_def = array('id' => '', 'class' => '', 'vers' => '');
        $opt_atttr = parse_args($atttr, $atttr_def);
        extract($opt_atttr);
        $g_id = ($id) ? ' id="' . $id . '"' : '';
        $g_cls = ($class) ? css_class($class) : '';

        $ver_cls = ($vers == 'side') ? ' token-balance-with-icon' : '';
        $ver_icon = ($vers == 'side') ? '<div class="token-balance-icon"><img src="' . asset('images/token-symbol-light.png') . '" alt=""></div>' : '';

        $base_cur = base_currency();
        $base_con = isset($data->$base_cur) ? to_num($data->$base_cur, 'auto')  : 0;
        $base_out = '<li class="token-balance-sub"><span class="lead">' . ($base_con > 0 ? $base_con : '~') . '</span><span class="sub">' . strtoupper($base_cur) . '</span></li>';

        $cur1_out = $cur2_out = '';
        if (gws('user_in_cur1', 'eth') != 'hide') {
            $cur1 = gws('user_in_cur1', 'eth');

            $cur1_con = (gws('pmc_active_' . $cur1) == 1) ? to_num($data->$cur1, 'auto') : 0;
            $cur1_out = ($cur1 != $base_cur) ? '<li class="token-balance-sub"><span class="lead">' . ($cur1_con > 0 ? $cur1_con : '~') . '</span><span class="sub">' . strtoupper($cur1) . '</span></li>' : '';
        }

        if (gws('user_in_cur2', 'btc') != 'hide') {
            $cur2 = gws('user_in_cur2', 'btc');
            $cur2_con = (gws('pmc_active_' . $cur2) == 1) ? to_num($data->$cur2, 'auto') : 0;
            $cur2_out = ($cur2 != $base_cur) ? '<li class="token-balance-sub"><span class="lead">' . ($cur2_con > 0 ? $cur2_con : '~') . '</span><span class="sub">' . strtoupper($cur2) . '</span></li>' : '';
        }

        $contribute = ($base_out || $cur1_out || $cur2_out) ? '<div class="token-balance token-balance-s2"><h6 class="card-sub-title">' . __('Your Contribution in') . '</h6><ul class="token-balance-list">' . $base_out . $cur1_out . $cur2_out . '</ul></div>' : '';

        $return = '<div' . $g_id . ' class="token-statistics card card-token' . $g_cls . '" style="background-image: linear-gradient(45deg, #037ec5 0%, #20a6f3 100%);">
        <div class="card-innr"><div class="token-balance' . $ver_cls . '">' . $ver_icon . '
        <div class="token-balance-text"><h6 class="card-sub-title">' . __('Token Balance Transaction') . '</h6>
        <span class="lead">' . to_num_token($user->tokenBalance2) .  ' <span>' . token('symbol') . ' <em class="fas fa-info-circle fs-11" data-toggle="tooltip" data-placement="right" title="' . __('Equivalent to') . ' ' . token_price($user->tokenBalance2, base_currency()) . ' ' . base_currency(true) . '"></em>
        </span></span>
        <span class="d-block text-alternet ucap fs-12">' . $percent . '%/Point/Day</span>
        <div class="gaps-1-3x"></div>
        <h6 class="card-sub-title">' . __('Affiliate has received') . '</h6>
        <div class="gaps-1-2x"></div>
        <h5>' . (round($user->affiliate_token,2) ?? 0) .'</h5>
        <h6 class="card-sub-title">' . __('Current basic rate of return: ') .  '</h6>
        <h5>'.($percent / 100) * (round($user->tokenPoint,2) ?? 0).'</h5>
        </div>
        
        </div>';
        // $return .= self::user_account_point_status();
        $return .= '<a href="#" class="btn btn-sm btn-auto btn-primary float-right" data-toggle="modal" data-target="#addTnx"><em class="fas fa-paper-plane"></em><span>Send ONE</span></a>';
        $return .= '</div></div>';

        return $return;
    }
}
