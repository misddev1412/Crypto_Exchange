<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Page\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ChangePageStatusController extends Controller
{
    public function changePublishStatus(Page $page): RedirectResponse{
        if ($page->toggleStatus('is_published')) {
            return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __('Successfully page status changed.'));
        }
        return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to change status. Please try again.'));
    }
}
