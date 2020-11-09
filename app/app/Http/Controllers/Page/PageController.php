<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Http\Requests\Page\PageRequest;
use App\Models\Page\Page;
use App\Services\Core\DataTableService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    protected $attributes;

    public function __construct()
    {
        $this->setAttributes();
    }

    public function setAttributes(): void
    {
        $this->attributes = [
            'title',
            'content',
            'meta_description',
            'meta_keywords',
            'is_published',
        ];
    }

    public function index(): View
    {
        $searchFields = [
            ['title', __('Title')],
            ['slug', __('Slug')],
        ];

        $orderFields = [
            ['title', __('Title')],
            ['slug', __('Slug')],
            ['is_published', __('Status')],
        ];

        $data['title'] = __('Pages');
        $queryBuilder = Page::orderBy('created_at', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->create($queryBuilder);

        return view('pages.page_management.index', $data);
    }

    public function create(): View
    {
        $data['title'] = __('Page Create');
        return view('pages.page_management.create', $data);
    }

    public function store(PageRequest $request): RedirectResponse
    {
        $attributes = $request->only($this->attributes);

        $attributes['content'] = $request->get('editor_content');
        try {
            Page::create($attributes);
        } catch (Exception $exception) {
            if ($exception->getCode() == 23000) {
                return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('Failed to create page for duplicate entry!'));
            }
            return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('Failed to create new page.'));
        }
        return redirect()->route('pages.index')->with(RESPONSE_TYPE_SUCCESS, __('Successfully '));
    }

    public function edit(Page $page): View
    {
        $data['title'] = __('Page Edit');
        $data['page'] = $page;
        return view('pages.page_management.edit', $data);
    }

    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        $attributes = $request->only($this->attributes);
        $attributes['content'] = $request->get('editor_content');

        try {
            $page->update($attributes);
        } catch (Exception $exception) {
            if ($exception->getCode() == 23000) {
                return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('Failed to update page for duplicate entry!'));
            }
            return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('Failed to update page.'));
        }
        return redirect()->route('pages.index', $page)->with(RESPONSE_TYPE_SUCCESS, __('Successfully page updated!'));
    }

    public function destroy(Page $page): RedirectResponse
    {
        if ($page->delete()) {
            return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __("The page has been deleted successfully."));
        }
        return redirect()->back()->with(RESPONSE_TYPE_ERROR, __("Failed to delete the page."));
    }
}
