<?php


namespace App\Services\Core;


use App\Models\Core\UserActivity;
use Jenssegers\Agent\Agent;

class UserActivityService
{
    public function store(string $userId, string $note): bool
    {
        $agent = new Agent();
        $device = null;

        $agent->isDesktop() ? $device = 'desktop' : null;
        $agent->isMobile() ? $device = 'mobile' : null;
        $agent->isRobot() ? $device = 'robot' : null;
        $agent->isTablet() ? $device = 'tablet' : null;
        $agent->isPhone() ? $device = 'phone' : null;

        $data = [
            'user_id' => $userId,
            'device' => $device,
            'browser' => $agent->browser() . ' version-' . $agent->version($agent->browser()),
            'operating_system' => $agent->platform(),
            'location' => geoip()->getLocation()->country,
            'ip_address' => geoip()->getClientIP(),
            'note' => $note,
        ];
        return UserActivity::create($data) ? true : false;
    }

    public function getUserActivities(string $user): array
    {
        $searchFields = [
            ['note', __('Activity')],
            ['device', __('Device')],
            ['location', __('location')],
            ['browser', __('Browser')],
            ['browser', __('Browser')],
            ['operating_system', __('Operating System')],
        ];

        $orderFields = [
            ['note', __('Activity')],
            ['device', __('Device')],
            ['location', __('location')],
            ['browser', __('Browser')],
            ['operating_system', __('Operating System')],
            ['created_at', __('Date')],
        ];

        $queryBuilder = UserActivity::where('user_id', $user)
            ->orderBy('created_at', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->create($queryBuilder);

        return $data;
    }
}
