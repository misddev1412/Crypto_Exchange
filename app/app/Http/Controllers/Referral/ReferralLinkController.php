<?php

namespace App\Http\Controllers\Referral;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReferralLinkController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $data['title'] = __('Referral');
        if (is_null($user->referral_code)) {
            $user->update(['referral_code' => Str::random()]);
            $user = $user->fresh();
        }

        $data['user'] = $user;
        return view('referral.link_show', $data);
    }
}
