<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Page\Page;

class ViewPageController extends Controller
{
    public function index(Page $page)
    {
        abort_unless($page->is_published, 404);
        $data['title'] = $page->title;
        $data['page'] = $page;
        
        return view('pages.page', $data);
    }
}
