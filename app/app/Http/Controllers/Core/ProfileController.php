<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Services\Core\UserActivityService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Core\{PasswordUpdateRequest, UserAvatarRequest, UserRequest};
use App\Services\Core\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    private $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    public function index(): View
    {
        $data = $this->service->profile();
        $data['title'] = __('Profile');

        return view('core.profile.index', $data);
    }

    public function edit(): View
    {
        $data = $this->service->profile();
        $data['title'] = __('Edit Profile');

        return view('core.profile.edit', $data);
    }

    public function update(UserRequest $request): RedirectResponse
    {
        $parameters = $request->only(['first_name', 'last_name', 'address']);
        if (Auth::user()->profile()->update($parameters)) {
            app(UserActivityService::class)->store(Auth::id(), 'update profile');
            return redirect()->route('profile.edit')->with(RESPONSE_TYPE_SUCCESS, __('Profile has been updated successfully.'));
        }

        return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to update profile.'));
    }

    public function changePassword(): View
    {
        $data = $this->service->profile();
        $data['title'] = __('Change Password');

        return view('core.profile.change_password', $data);
    }

    public function updatePassword(PasswordUpdateRequest $request): RedirectResponse
    {
        $response = $this->service->updatePassword($request);

        if ($response[RESPONSE_STATUS_KEY]){
            if (app(UserActivityService::class)->store(Auth::id(), 'update password')){
                Auth::logout();
                return redirect()->route('login')->with(RESPONSE_TYPE_SUCCESS, $response[RESPONSE_MESSAGE_KEY]);
            }
        }
        return redirect()->back()->with(RESPONSE_TYPE_ERROR, $response[RESPONSE_MESSAGE_KEY]);
    }

    public function avatarUpdate(UserAvatarRequest $request): JsonResponse
    {
        $response = $this->service->avatarUpload($request);

        return response()->json($response);
    }
}
