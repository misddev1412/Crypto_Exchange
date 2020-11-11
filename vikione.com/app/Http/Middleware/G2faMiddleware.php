<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;

class G2faMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$this->isG2faActive()) return $next($request);

        if($this->isG2faAuthenticated($request)){
            return $next($request);
        }else{
            if(isset($request->one_time_password)){
                session()->flash('2fa_notice', 'Invalid Google 2FA Code.');
            }
        }
        if(is_maintenance()){
            return redirect()->route('admin.auth.2fa');
        }
        return redirect()->route('auth.2fa');
    }

    protected function isG2faActive($value='')
    {
        if(Auth::check()){
            $user = Auth::user();
            if($user->google2fa == 1 && !empty($user->google2fa_secret)){
                return true;
            }
            return false;
        }
        return false;
    }

    protected function isG2faAuthenticated(Request $request)
    {
        if($this->is2faAuth()) return true;

        if(!isset($request->one_time_password)) return false;

        if($this->getUser() && $verifyOTP = $this->verifyOTP($this->getUser('google2fa_secret'), $request->one_time_password)){
            $data = [
                'id' => Auth::id(),
                'time' => now()
            ];
            session()->put('_g2fa_session', $data);
            return true;
        }
        return false;
    }

    protected function is2faAuth()
    {
        if($this->getUser()){
            if(session()->has('_g2fa_session')){
                $user = session('_g2fa_session');
                $id = isset($user['id']) ? $user['id'] : null;
                if($this->getUser()->id == $id) return true;
                return false;
            }
            return false;
        }
        if(session()->has('_g2fa_session')){
            session()->forget('_g2fa_session');
        }
        return false;
    }

    protected function verifyOTP($secret, $one_time_password)
    {
        $g2fa = new Google2FA();
        return $g2fa->verify(
                $one_time_password,
                $secret
        );
    }

    protected function getUser($data=null)
    {
        if(Auth::check() ){
            return empty($data) ? Auth::user() : Auth::user()->$data;
        }
        return false;
    }
}
