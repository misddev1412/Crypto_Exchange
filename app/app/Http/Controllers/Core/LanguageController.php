<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\LanguageRequest;
use App\Models\Core\Language;
use App\Services\Core\{DataTableService, FileUploadService, LanguageService};
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\{Facades\Cache, Facades\DB};
use Illuminate\View\View;

class LanguageController extends Controller
{
    public $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    public function index(): View
    {
        $searchFields = [
            ['name', __('Name')],
            ['short_code', __('Short Code')],
        ];

        $orderFields = [
            ['id', __("Serial")],
            ['name', __('Name')],
            ['short_code', __('Short Code')],
        ];

        $queryBuilder = Language::orderBy('id', 'desc');
        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->create($queryBuilder);
        $data['title'] = __('Languages');
        return view('core.languages.index', $data);
    }

    public function create(): View
    {
        $data['title'] = __('Create New Language');
        return view('core.languages.create', $data);
    }

    public function store(LanguageRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $params = $request->only(['name', 'short_code']);

            if ($request->hasFile('icon')) {
                $filePath = config('commonconfig.language_icon');
                $fileName = $params['short_code'];
                $params['icon'] = app(FileUploadService::class)->upload($request->file('icon'), $filePath, $fileName, $prefix = '', $suffix = '', $disk = 'public', $width = 120, $height = 80);
            }

            $language = Language::create($params);

            if (empty($language)) {
                throw new Exception(__('Failed to create language.'));
            }

            $this->languageService->addLanguage($params['short_code']);

            $this->cache($language);

        } catch (Exception $exception) {

            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to create language.'));
        }

        DB::commit();
        return redirect()->route('languages.index')->with(RESPONSE_TYPE_SUCCESS, __('Language [:lang] has been created successfully.', ['lang' => $params['short_code']]));
    }

    private function cache(Language $language): void
    {
        $languages = Cache::get('languages');

        $languages[$language->short_code] = [
            'name' => $language->name,
            'icon' => $language->icon
        ];

        Cache::set('languages', $languages);
    }

    public function edit(Language $language): View
    {
        $data['language'] = $language;
        $data['title'] = __('Edit Language');
        return view('core.languages.edit', $data);
    }

    public function update(LanguageRequest $request, Language $language): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $params = $request->only(['name', 'short_code', 'is_active']);

            if ($language->short_code == settings('lang')) {
                $params['is_active'] = ACTIVE;
            }

            if ($params['short_code'] != $language->short_code) {
                $isRenamed = $this->languageService->rename($language->short_code, $params['short_code']);
                if (!$isRenamed) {
                    throw new Exception(__('Failed to rename file.'));
                }
            }

            if ($request->hasFile('icon')) {
                $filePath = config('commonconfig.language_icon');
                $fileName = $params['short_code'];
                $params['icon'] = app(FileUploadService::class)->upload($request->file('icon'), $filePath, $fileName, $prefix = '', $suffix = '', $disk = 'public', $width = 120, $height = 80);
            }

            $language->update($params);
            $this->cache($language->fresh());
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, $exception->getMessage());
        }
        DB::commit();

        return redirect()->route('languages.index')->with(RESPONSE_TYPE_SUCCESS, __('Language [:lang] has been updated successfully.', ['lang' => $params['short_code']]));

    }

    public function destroy(Language $language): RedirectResponse
    {
        DB::beginTransaction();
        try {
            if ($language->short_code == settings('lang')) {
                throw new Exception(__('Default language cannot be deleted.'));
            }

            $languages = Cache::get('languages');
            unset($languages[$language->short_code]);
            Cache::set('languages', $languages);
            $language->delete();

        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->route('languages.index')->with(RESPONSE_TYPE_ERROR, $exception->getMessage());
        }

        DB::commit();
        return redirect()->route('languages.index')->with(RESPONSE_TYPE_SUCCESS, __('Language [:lang] has been deleted successfully.', ['lang' => $language->short_code]));
    }

    public function settings(): View
    {
        $data['title'] = __('Language Settings');
        return view('core.languages.settings', $data);
    }

    public function getTranslation(): JsonResponse
    {
        $translations = $this->languageService->getTranslations();
        return response()->json($translations);
    }

    public function settingsUpdate(Request $request): JsonResponse
    {
        $this->languageService->saveTranslations($request->translations);
        return response()->json(['type' => 'success', 'message' => __('Saved successfully.')]);
    }

    public function sync(): JsonResponse
    {
        $response = $this->languageService->sync();
        return response()->json($response);
    }
}
