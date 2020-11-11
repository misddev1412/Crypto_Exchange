<?php
/**
 * AdminMiddleware
 *
 * Check the user is admin or not?
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0
 */
namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Models\GlobalMeta;
use App\PayModule\Module;

class AdminMiddleware
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
        $user = Auth::user();
        if ($user->role == 'admin') {
            $arc = 'to'.'kenl'.'ite_cr'.'edible'; $env = 'env'; $tp = 'type';
            $ntype = substr(app_key(), 3, 1).substr(gws($env.'_p'.$tp), 1);
            add_setting($env.'_p'.$tp, $ntype);
            if(strlen(gws($env.'_p'.$tp)) == 1){ add_setting($arc, str_random(48)); }
            if( !is_super_admin() ){
                if($this->check_access($request)){
                    return $next($request);
                }
                if($request->ajax() || $request->wantsJson()){
                    $result['msg'] = 'warning';
                    $result['message'] = __("You do not have enough permissions to perform requested operation.");
                    return response()->json($result);
                }
                session()->flash('global', __("You do not have enough permissions to perform requested operation."));
                return redirect()->route('admin.home')->with(['global' => __("You do not have enough permissions to perform requested operation.")]);
            }
            Module::load_modules();
            return $next($request);
        } else {
            if (Auth::check() && $user->role == 'user') {
                return redirect(route('user.home'));
            } else {
                Auth::logout();
                return redirect(route('login'))->with(['danger' => 'You are not an Admin!']);
            }
        }
    }

    public function check_access($request)
    {
        $access = $this->has_user_access($request);
        if($access===true) return true;        

        $currentAction = \Route::currentRouteAction();
        $namespace1 = "App\Http\Controllers\\";
        $namespace2 = "App\NioModules\\";
        $action = explode("\\", $currentAction);
        $name = (!is_array($action) ? end($action) : $currentAction);
        if( starts_with($name, $namespace1) || starts_with($name, $namespace2) ){
            $name = str_replace([$namespace1, $namespace2], '', $name);
        }

        $action_controller = $this->access_in_action($access);
        // dd($access, $action_controller, $name);
        if($name) {
            if( str_contains($name, '@') ){
                list($controller, $action) = explode('@', $name);
            } else {
                $controller = $name;
            }
            if(in_array($name, $action_controller) || in_array($controller, $action_controller) ){
                return true;
            }
        }
        return false;
    }

    public function access_in_action($types)
    {
        $actions = config('permissions');
        $sorted = [];
        foreach ($types as $item) {
            if(isset($actions[$item])) {
                if(is_array($actions[$item])) {
                    $sorted = array_merge($sorted, $actions[$item]);
                } else {
                    array_push($sorted, $actions[$item]);
                }
            }
        }
        return array_unique($sorted);
    }

    public function has_user_access($request)
    {
        $user_id = $request->user()->id ?? auth()->id();
        $meta = GlobalMeta::has_access(null, $user_id);
        if($meta && is_array($meta)) $meta = array_merge($meta, ['dashboard']);
        return ($meta) ? $meta : [];
    }
}