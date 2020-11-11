<?php
/**
 * Public Content
 *
 * Manage the public content
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0.1
 */
namespace App\Http\Controllers;

use Auth;
use QRCode;
use Cookie;
use IcoData;
use App\Models\KYC;
use App\Models\User;
use App\Models\Page;
use App\Models\Setting;
use App\Helpers\IcoHandler as TLite;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;
use Illuminate\Http\Request;

class PublicController extends Controller
{

    /**
     * Set Language
     *
     * @version 1.1
     * @since 1.0
     * @return back()
     */
    public function set_lang(Request $request)
    {
        $lang = isset($request->lang) ? $request->lang : 'en';
        $_key = Cookie::queue(Cookie::make('app_language', $lang, (60 * 24 * 365)));
        return back();
    }

    /**
     * Show the QR Code
     *
     * @version 1.0.0
     * @since 1.0
     * @return \Illuminate\Http\Response
     */
    public function qr_code(Request $request)
    {
        $text = $request->get('text');
        if(empty($text)) return abort(404);
        return response(QRCode::text($text)->png(), 200)->header("Content-Type", 'image/png');
    }

    /**
     * Show the kyc application
     *
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function kyc_application()
    {
        $countries = \IcoHandler::getCountries();
        $sidebar = 'hide';
        $title = KYC::documents();
        if (Auth::check() && Auth::user()->role == 'admin') {
            return view('public.kyc-application', compact('countries', 'sidebar', 'title'));
        } else {
            if (get_setting('kyc_public') == 1) {
                if (Auth::check()) {
                    return redirect(route('user.kyc.application'));
                }
                $input_email = true;
                $title = KYC::documents();
                return view('public.kyc-application', compact('countries', 'sidebar', 'input_email', 'title'));
            } else {
                if (Auth::check()) {
                    return redirect(route('user.kyc.application'));
                }
                return redirect(route('login'));
            }
        }
    }

    /**
     * Show the Pages Dynamically
     *
     * @return \Illuminate\Http\Response
     * @version 1.1
     * @since 1.0
     * @return void
     */
    public function site_pages($slug = '')
    {
        $page = Page::where('slug', $slug)->orwhere('custom_slug', $slug)->where('status', 'active')->first();

        if ($page != null) {
            if (Auth::check()) {
                $user = Auth::user();
                if ($user->email_verified_at != null && $user->status == 'active') {
                    if (is_2fa_lock()) {
                        return view('public.page', compact('page'));
                    } else {
                        if ($user->role == 'admin') {
                            return view('admin.page', compact('page'));
                        } else {
                            return view('user.page', compact('page'));
                        }
                    }
                } else {
                    return view('public.page', compact('page'));
                }
            } else {
                if ($page->public) {
                    return view('public.page', compact('page'));
                } else {
                    return redirect()->route('login');
                }
            }
        } else {
            return redirect()->route('login');
        }
        return abort(404);
    }

    public function database()
    {
        $outputLog = new BufferedOutput;
        try{
            Artisan::call('migrate', ["--force"=> true], $outputLog);
        }
        catch(Exception $e){
            return view('error.500', $outputLog);
        }
        return redirect(route('home'));
       
    }

    public function update_check()
    {
        $updater = (new TLite);
        $check = $updater->build_app_system();
        $havel = Setting::where('field', 'LIKE', "%_lkey")->first();
        if( $havel && str_contains($havel->value, gdmn(1))){
            add_setting('site_a'.'pi_s'.'ecret', str_random(4).gdmn(1).str_random(4));
        } else {
            add_setting('site_ap'.'i_sec'.'ret', str_random(16) );
        }

        if(!$check) add_setting('ni'.'o_lk'.'ey', str_random(28));
        return redirect(route('home'));
    }

    /**
     * Referral 
     *
     * @version 1.0.0
     * @since 1.0.3
     * @return void
     */
    public function referral(Request $request)
    {
        $key = $request->get('ref');
        $expire =  (60*24*30);

        if ($key != NULL) {
            $ref_user = (int)(str_replace(config('icoapp.user_prefix'), '', $key));
            if($ref_user > 0){
                $user = User::where('id',$ref_user)->where('email_verified_at', '!=', null)->first();
                if ($user) {
                    $_key = Cookie::queue(Cookie::make('ico_nio_ref_by', $ref_user, $expire));
                }
            }
        }
        return redirect()->route('register');
    }
}
