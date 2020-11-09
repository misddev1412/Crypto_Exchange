<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Core\Notification;
use App\Services\Core\DataTableService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $data['title'] = __('Notices');

        $searchFields = [
            ['data', __('Notice')],
        ];

        $orderFields = [
            ['id', __('Serial')],
            ['data', __('Notice')],
            ['created_at', __('Date')],
            ['read_at', __('Status')],
        ];


        $queryBuilder = Notification::where('user_id', Auth::id())->orderBy('id', 'desc');
        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->create($queryBuilder);

        return view('core.notifications.index', $data);
    }

    public function markAsRead(Notification $notification): RedirectResponse
    {
        if ($notification->markAsRead()) {
            return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __('The notice has been marked as read.'));
        }

        return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to mark as read.'));
    }

    public function markAsUnread(Notification $notification): RedirectResponse
    {
        if ($notification->markAsUnread()) {
            return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __('The notice has been marked as unread.'));
        }

        return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to mark as unread.'));
    }
}
