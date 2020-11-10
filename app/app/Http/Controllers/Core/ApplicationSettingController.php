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

    public function edit(string $type = "", string $subType = ""): View
    {
        if(!$type){
            $type = array_key_first($this->applicationSettingService->settingsConfigurations);
        }

        if(!$subType){
            $subType = array_key_first($this->applicationSettingService->settingsConfigurations[$type]['settings']);
        }

        abort_if(!isset($this->applicationSettingService->settingsConfigurations[$type]['settings'][$subType]), 404);

        $data['settings'] = $this->applicationSettingService->loadForm($type, $subType);
        $data['type'] = $type;
        $data['sub_type'] = $subType;
        $data['title'] = __('Edit - :type', ['type' => ucfirst($type)]);
        return view('core.application_settings.edit', $data);
    }

    public function update(Request $request, string $type, string $subType): RedirectResponse
    {
        if(!isset($this->applicationSettingService->settingsConfigurations[$type]['settings'][$subType])){
            return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('Failed to update settings.'));
        }

        $response = $this->applicationSettingService->update($request, $type, $subType);
        $status = $response[RESPONSE_STATUS_KEY] ? RESPONSE_TYPE_SUCCESS : RESPONSE_TYPE_ERROR;

        return redirect()->route('application-settings.edit', [$type, $subType])->withInput($response['inputs'])->withErrors($response['errors'])->with($status, $response[RESPONSE_MESSAGE_KEY]);
    }
}
