<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\NoticeRequest;
use App\Models\Core\Notice;
use App\Services\Core\DataTableService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NoticesController extends Controller
{
    public function index(): View
    {
        $searchFields = [
            ['title', __('Title')],
        ];
        $orderFields = [
            ['type', __('Type')],
            ['status', __('Status')],
            ['start_at', __('Start Time')],
            ['end_at', __('End Time')],
        ];

        $filters = [
            ['type', __('Type'), notices_types()],
            ['status', __('Status'), active_status()],
        ];
        $queryBuilder = Notice::orderBy('id', 'desc');
        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->setFilterFields($filters)
            ->create($queryBuilder);
        $data['title'] = __('Notices');

        return view('core.notices.index', $data);
    }

    public function create(): View
    {
        $data['title'] = __('Create Notice');

        return view('core.notices.create', $data);
    }

    public function store(NoticeRequest $request): RedirectResponse
    {
        $noticeInput = $request->only(['title', 'description', 'start_at', 'end_at', 'is_active', 'type', 'visible_type']);
        $noticeInput['created_at'] = Auth::user()->id;
        $notice = Notice::create($noticeInput);

        if (!empty($notice)) {
            return redirect()->route('notices.index')->with(RESPONSE_TYPE_SUCCESS, __('Notice has been created successfully.'));
        }

        return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('Failed to create notice.'));
    }

    public function edit(Notice $notice): View
    {
        $data['notice'] = $notice;
        $data['title'] = __('Edit Notices');

        return view('core.notices.edit', $data);
    }

    public function update(NoticeRequest $request, Notice $notice): RedirectResponse
    {
        $systemNotice = $request->only(['title', 'description', 'start_at', 'end_at', 'is_active', 'type', 'visible_type']);

        if ($notice->update($systemNotice)) {
            return redirect($this->getRedirectUri())->with(RESPONSE_TYPE_SUCCESS, __('Notice has been updated successfully.'));
        }

        return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('Failed to update notice.'));
    }

    public function destroy(Notice $notice): RedirectResponse
    {
        if ($notice->delete()) {
            return back()->with(RESPONSE_TYPE_SUCCESS, __('Notice has been deleted successfully.'));
        }

        return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('Failed to delete notice.'));
    }
}
