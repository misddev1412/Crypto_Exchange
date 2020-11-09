<?php

namespace App\Http\Controllers\UserActivity;

use App\Http\Controllers\Controller;
use App\Models\Core\UserActivity;
use App\Services\Core\DataTableService;
use App\Services\Core\UserActivityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserActivityController extends Controller
{
    public function index(): View
    {
        $data = app(UserActivityService::class)->getUserActivities(Auth::id());
        $data['title'] = __('My Activities');
        return view('user_activity.user.my_activity', $data);
    }
}
