<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Core\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminUserSearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'p-srch' => 'required'
        ]);

        $searchFields = [
            'original' => [
                'email',
                'username',
            ],
            'relations' => [
                'profile' => [
                    'first_name',
                    'last_name',
                ]
            ]
        ];

        $users = User::with('profile')
            ->where('users.assigned_role', USER_ROLE_ADMIN)
            ->where('status', STATUS_ACTIVE)
            ->where(function ($query) use ($searchFields, $request) {
                $query->likeSearch($searchFields, $request->get('p-srch'));
            })
            ->get();

        $response = [];
        if (!$users->isEmpty()) {
            foreach ($users as $user) {
                $response[] = [
                    'id' => $user->id,
                    'name' => $user->profile->full_name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'avatar' => get_avatar($user->avatar)
                ];
            }
        }

        return response()->json($response);
    }
}
