<?php

namespace App\Http\Controllers\UserActivity;

use App\Http\Controllers\Controller;
use App\Models\Core\User;
use App\Models\Core\UserActivity;
use App\Services\Core\DataTableService;
use App\Services\Core\UserActivityService;
use Illuminate\Support\Facades\Auth;

class AdminActivityController extends Controller
{
    public function index(User $user)
    {
        $data = app(UserActivityService::class)->getUserActivities($user->id);
        $data['title'] = __('User Activities');
        return view('user_activity.admin.users_activity', $data);
    }
}
