<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Coin\Coin;
use App\Services\Core\ApplicationSettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class ApplicationSettingController extends Controller
{
    public $applicationSettingService;

    public function __construct()
    {
        $this->addBTCForkedCoinSettingToSettingConfig();
        $this->applicationSettingService = app(ApplicationSettingService::class);
    }

    private function addBTCForkedCoinSettingToSettingConfig()
    {
        $btcForkedCoins = Coin::whereJsonContains('api->selected_apis', API_BITCOIN)
            ->select(['symbol', 'name'])
            ->where('is_active', ACTIVE)
            ->get();

        $settingFields = [];

        foreach ($btcForkedCoins as $forkedCoin) {
            $symbol = strtolower($forkedCoin->symbol);
            $settingName = strtolower($forkedCoin->name);
            foreach (get_bitcoin_fields() as $fieldName => $attributes) {
                $settingFields[$settingName][$symbol . $fieldName] = $attributes;
            }
        }
        $settings = config('appsettings.settings.api_settings.settings');
        $updatedSettings = array_merge($settings, $settingFields);
        Config::set('appsettings.settings.api_settings.settings', $updatedSettings);
    }
    public function index(): RedirectResponse{
        $type = array_key_first($this->applicationSettingService->settingsConfigurations);
        $sub_type = array_key_first(current($this->applicationSettingService->settingsConfigurations)['settings']);
        return redirect()->route('application-settings.edit', ['type' => $type, 'sub_type' => $sub_type]);
    }
    public function edit(string $type, string $sub_type): View
    {
        abort_if(!isset($this->applicationSettingService->settingsConfigurations[$type]['settings'][$sub_type]), 404);

        $data['settings'] = $this->applicationSettingService->loadForm($type, $sub_type);
        $data['type'] = $type;
        $data['sub_type'] = $sub_type;
        $data['title'] = __('Edit - :type', ['type' => ucfirst($type)]);
        return view('core.application_settings.edit', $data);
    }

    public function update(Request $request, string $type, string $sub_type): RedirectResponse
    {
        if(!isset($this->applicationSettingService->settingsConfigurations[$type]['settings'][$sub_type])){
            return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('Failed to update settings.'));
        }

        $response = $this->applicationSettingService->update($request, $type, $sub_type);
        $status = $response[RESPONSE_STATUS_KEY] ? RESPONSE_TYPE_SUCCESS : RESPONSE_TYPE_ERROR;

        return redirect()->route('application-settings.edit', [$type, $sub_type])->withInput($response['inputs'])->withErrors($response['errors'])->with($status, $response[RESPONSE_MESSAGE_KEY]);
    }
}
