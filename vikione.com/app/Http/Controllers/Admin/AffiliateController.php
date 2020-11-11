<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AffiliateController extends Controller {
    public function index(Request $request)
    {
        $users = User::where('status', 'active')->whereNotNull('email_verified_at')->where('role', '!=', 'admin')->get();
        return view('admin.affiliate',compact('users'));
    }

    public function floor(Request $request)
    {
        $uid = $request->input('uid');
        $token = $request->input('token');
        $this->getRefFloor($users, User::where('status', 'active')->where('id', $uid)->first(),$token, 1);
        
        if ($request->ajax()) {
            return response()->json([
                'users' => $users
            ]);
        }
    }

    private function getRefFloor(&$users,$user, $tokenAffiliate, $floor) {
        $user = User::where('status', 'active')->where('id', $user->referral)->first();
        if($user) {
            $users[] = [
                'floor' => $floor,
                'email' => $user->email .' - <b>'. $user->affiliate .'</b>',
                'token' => ($floor > 1) ? $tokenAffiliate * (get_setting('affiliate_'. $user->affiliate . '_indirect') / 100):  0,
                'point' => ($floor == 1) ? $tokenAffiliate * (get_setting('affiliate_'. $user->affiliate . '_direct') / 100):  0,
            ];
            $this->getRefFloor($users, $user,$tokenAffiliate, ++$floor);
        }
    }
}
