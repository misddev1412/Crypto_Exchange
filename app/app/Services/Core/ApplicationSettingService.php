<?php

namespace App\Services\Core;

use App\Models\Core\ApplicationSetting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\HtmlString;

class ApplicationSettingService
{
    public $settingsConfigurations;
    protected $fieldValues = [];
    protected $type;
    protected $subType;
    protected $errorMessages = [];

    public function __construct()
    {
        $this->settingsConfigurations = config("appsettings.settings");
    }

    public function update(Request $request, $type, $subType)
    {
        $this->type = $type;
        $this->subType = $subType;
        $settingsRequest = $request->settings;
        if(empty($settingsRequest)){
            $settingsRequest = [];
        }
        $uploadAbleImages = [];

        foreach ($settingsRequest as $field => $value) {
            $this->validate($field, $value);
            if (is_array($value)) {
                $settingsRequest[$field] = json_encode($value);
            } elseif (is_a($value, 'Illuminate\Http\UploadedFile')) {
                $uploadAbleImages[$field] = $value;
            } elseif (
                isset($this->settingsConfigurations[$this->type]['settings'][$this->subType][$field]['encryption']) &&
                $this->settingsConfigurations[$this->type]['settings'][$this->subType][$field]['encryption']
            ) {
                $settingsRequest[$field] = encrypt($value);
            }
        }

        if (!empty($this->errorMessages)) {
            return [
                RESPONSE_STATUS_KEY => false,
                RESPONSE_MESSAGE_KEY => __('Invalid data in field(s).'),
                'errors' => $this->errorMessages,
                'inputs' => $settingsRequest
            ];
        }

        $existingSettingsFromDatabase = ApplicationSetting::whereIn('slug', array_keys($settingsRequest))->get()->toArray();
        $updateAbleSettings = array_diff_assoc($settingsRequest, $existingSettingsFromDatabase);
        $imageUploadCount = 0;
        if (!empty($uploadAbleImages)) {
            $this->uploadImages($uploadAbleImages, $updateAbleSettings, $imageUploadCount);
        }


        if (!empty($updateAbleSettings)) {
            $updateCount = $imageUploadCount;
            foreach ($updateAbleSettings as $field => $value) {
                $attributes = ['slug' => $field, 'value' => $value];
                $conditions = ['slug' => $field];
                if ($isUpdate = ApplicationSetting::updateOrCreate($conditions, $attributes)) {
                    if ($isUpdate->wasRecentlyCreated || $isUpdate->wasChanged()) {

                        $updateCount++;
                    }
                }
            }

            if ($updateCount > 0) {
                $cachedSettings = Cache::get("appSettings");
                Cache::forget("appSettings");
                if (!empty($cachedSettings)) {
                    $cachedSettings = array_merge($cachedSettings, $updateAbleSettings);
                } else {
                    $cachedSettings = $updateAbleSettings;
                }
                Cache::forever("appSettings", $cachedSettings);

                $message = __(':updateCount setting(s) have been updated!', ['updateCount' => $updateCount]);

                return [
                    RESPONSE_STATUS_KEY => true,
                    RESPONSE_MESSAGE_KEY => $message,
                    'errors' => [],
                    'inputs' => []
                ];
            }
        }

        return [
            RESPONSE_STATUS_KEY => false,
            RESPONSE_MESSAGE_KEY => __('There is nothing to be changed!'),
            'errors' => [],
            'inputs' => []
        ];
    }

    protected function validate($field, $value)
    {
        $fieldConfiguration = $this->settingsConfigurations[$this->type]['settings'][$this->subType][$field];

        if (isset($fieldConfiguration['validation']) && !empty($fieldConfiguration['validation'])) {
            $rules = explode('|', $fieldConfiguration['validation']);
            foreach ($rules as $rule) {
                $this->_validate($field, $value, $rule);
            }
        }
    }

    protected function _validate($field, $value, $rule)
    {
        switch ($rule) {
            case 'required':
                if ($value == "") {
                    $this->errorMessages[$field][] = __('This field is required.');
                }
                break;
            case 'numeric' :
                if (!is_numeric($value)) {
                    $this->errorMessages[$field][] = __('This field must be numeric.');
                }
                break;
            case 'integer':
                if (filter_var($value, FILTER_VALIDATE_INT) === false) {
                    $this->errorMessages[$field][] = __('This field must be integer.');
                }
                break;
            case 'digit' :
                if (!ctype_digit($value))
                    $this->errorMessages[$field][] = __('This field must be between 0 and 9 digits.');
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errorMessages[$field][] = __('This field must be email.');
                }
                break;
            case 'boolean':
                if (!is_bool($value)) {
                    $this->errorMessages[$field][] = __('This field must be boolean.');
                }
                break;
            case 'image':
                if (!is_a($value, 'Illuminate\Http\UploadedFile')
                    || !in_array($value->clientExtension(), ['png', 'jpg', 'jpeg', 'gif'])
                ) {
                    $this->errorMessages[$field][] = __('This field must be [png, jpg, jpge, gif].');
                }

                break;
            case 'array':
                if (!is_array($value) || empty($value)) {
                    $this->errorMessages[$field][] = __('This field must be array.');
                }

                break;
            case strpos($rule, 'size:') !== false:
                $keyValue = explode(':', $rule);
                $imageSize = $value->getSize() / 1024;

                if (!is_a($value, 'Illuminate\Http\UploadedFile') || $imageSize > $keyValue[1]) {
                    $this->errorMessages[$field][] = __('This field may not be greater than :size.', ['size' => $keyValue[1]]);
                }
                break;
            case strpos($rule, 'min:') !== false :
                $keyValue = explode(':', $rule);

                if (!is_numeric($keyValue[1]) || $value < $keyValue[1]) {
                    $this->errorMessages[$field][] = __('This field must be at least :min.', ['min' => $keyValue[1]]);
                }
                break;
            case strpos($rule, 'max:') !== false :
                $keyValue = explode(':', $rule);

                if (!is_numeric($keyValue[1]) || $value > $keyValue[1]) {
                    $this->errorMessages[$field][] = __('This field may not be greater than :max.', ['max' => $keyValue[1]]);
                }

                break;
            case strpos($rule, 'in:') !== false :
                $keyValue = explode(':', $rule);
                $matchValues = function_exists($keyValue[1]) ? array_keys(call_user_func($keyValue[1])) : explode(',', $keyValue[1]);

                if (is_array($value) && !empty(array_diff($value, $matchValues))) {
                    $this->errorMessages[$field][] = __('The selected value is invalid.');
                } elseif (!is_array($value) && !in_array($value, $matchValues)) {
                    $this->errorMessages[$field][] = __('The selected value is invalid.');
                }
                break;
            default:
        }
    }

    private function uploadImages($uploadAbleImages, &$updateAbleSettings, &$imageUploadCount)
    {
        $fileUploadService = app(FileUploadService::class);
        foreach ($uploadAbleImages as $field => $file) {
            $filePath = config('commonconfig.path_image');
            $width = isset($this->settingsConfigurations[$this->type]['settings'][$this->subType][$field]['width']) ? $this->settingsConfigurations[$this->type]['settings'][$this->subType][$field]['width'] : null;
            $height = isset($this->settingsConfigurations[$this->type]['settings'][$this->subType][$field]['height']) ? $this->settingsConfigurations[$this->type]['settings'][$this->subType][$field]['height'] : null;

            $uploadedFileName = $fileUploadService->upload($file, $filePath, $field, '', '', 'public', $width, $height);

            if (empty($uploadedFileName)) {
                unset($updateAbleSettings[$uploadedFileName]);
                continue;
            }
            $imageUploadCount++;
            $updateAbleSettings[$field] = $uploadedFileName;
            session()->flash('image_updated', true);
        }
    }

    public function loadForm($settingGroup = null, $subSettingGroup = null, $viewOnly = false)
    {
        $this->type = $settingGroup;
        $this->subType = $subSettingGroup;

        $output = '';

        $settingGroup = config('appsettings.settings.' . $settingGroup);
        $common_wrapper = config('appsettings.common_wrapper');
        foreach ($settingGroup['settings'][$subSettingGroup] as $key => $value) {
            $common_input_options = config('appsettings.common_' . $value['field_type'] . '_input_wrapper');
            $input_class = isset($value['input_class']) ? __($value['input_class']) : (isset($common_input_options['input_class']) ? $common_input_options['input_class'] : '');
            $place_holder = isset($value['placeholder']) ? __($value['placeholder']) : "";

            if (isset($value['previous'])) {
                $fieldValue = $this->fieldValues[$value['previous']];
            } elseif (in_array($value['field_type'], ['checkbox', 'select', 'radio'])) {
                if (is_array($value['field_value'])) {
                    $fieldValue = $value['field_value'];
                } else {
                    $fieldValue = call_user_func_array($value['field_value'], []);
                }
            } elseif ($value['field_type'] == 'switch') {
                $fieldValue = [0, 1];
            } else {
                $fieldValue = '';
            }

            $this->fieldValues[$key] = $fieldValue;
            $value_data = old($key, (settings($key) === false || settings($key) === null) ? (isset($value['default']) ? $value['default'] : null) : settings($key));

            if (session()->has('errors')) {
                $errors = session()->get('errors');
                $this->errorMessages = $errors->getBag('default')->messages();
            }

            $input_start_tag = isset($value['input_start_tag']) ? $value['input_start_tag'] : (isset($common_input_options['input_start_tag']) ? $common_input_options['input_start_tag'] : '');
            $input_end_tag = isset($value['input_end_tag']) ? $value['input_end_tag'] : (isset($common_input_options['input_end_tag']) ? $common_input_options['input_end_tag'] : '');
            $output .= isset($key['section_start_tag']) ? $key['section_start_tag'] : (isset($common_wrapper['section_start_tag']) ? $common_wrapper['section_start_tag'] : '');
            $output .= isset($key['slug_start_tag']) ? $key['slug_start_tag'] : (isset($common_wrapper['slug_start_tag']) ? $common_wrapper['slug_start_tag'] : '');
            $output .= __($value['field_label']);
            $output .= isset($value['slug_end_tag']) ? $value['slug_end_tag'] : (isset($common_wrapper['slug_end_tag']) ? $common_wrapper['slug_end_tag'] : '');
            $output .= isset($value['value_start_tag']) ? $value['value_start_tag'] : (isset($common_wrapper['value_start_tag']) ? $common_wrapper['value_start_tag'] : '');
            if ($viewOnly) {
                $output .= $this->_viewOutput($key, $value['field_type'], $fieldValue, $value_data);
            } else {
                $output .= $this->{'_' . $value['field_type']}($key, $fieldValue, $input_class, $value_data, $place_holder, $input_start_tag, $input_end_tag);
            }
            $output .= isset($value['value_end_tag']) ? $value['value_end_tag'] : (isset($common_wrapper['value_end_tag']) ? $common_wrapper['value_end_tag'] : '');
            $output .= isset($value['section_end_tag']) ? $value['section_end_tag'] : (isset($common_wrapper['section_end_tag']) ? $common_wrapper['section_end_tag'] : '');
        }

        $settingSections = [];
        foreach ($this->settingsConfigurations as $key => $value) {
            $settingSections[$key] = [
                'icon' => $value['icon'],
                'settings' => array_keys($value['settings']),
            ];
        }
        return ['html' => new HtmlString($output), 'settingSections' => $settingSections];
    }

    private function _viewOutput($key, $fieldType, $fieldValue, $value_data)
    {
        if (in_array($fieldType, ['checkbox'])) {
            if (is_json($value_data)) {
                $value_data = json_decode($value_data, true);
                $output = implode(', ', array_intersect_key($fieldValue, array_flip($value_data)));
                return !empty($output) ? $output : $value_data;
            } elseif (is_array($value_data)) {
                return implode(', ', array_intersect_key($fieldValue, array_flip($value_data)));
            } elseif (!empty($fieldValue)) {
                return isset($fieldValue[$value_data]) ? $fieldValue[$value_data] : $value_data;
            } else {
                return $value_data;
            }
        } elseif ($fieldType == 'switch') {
            return isset($fieldValue[$value_data]) && $fieldValue[$value_data] ? __('Enabled') : __('Disabled');
        } elseif ($fieldType == 'image') {
            return '<img width="80" src="' . get_image($value_data) . '" />';
        } elseif (!empty($fieldValue)) {
            return isset($fieldValue[$value_data]) ? $fieldValue[$value_data] : $value_data;
        } else {
            return $value_data;
        }
    }

    private function _text($key, $data_array, $input_class, $value_data, $place_holder, $input_start_tag, $input_end_tag)
    {
        $error = '';
        if (isset($this->errorMessages[$key])) {
            $error = '<span class="invalid-feedback">' . $this->errorMessages[$key][0] . '</span>';
            $input_class = $input_class . ' is-invalid';
        }

        $output = $input_start_tag . '<input class="' . $input_class . '" type="text" value="' . $value_data . '" name="settings[' . $key . ']" placeholder="' . $place_holder . '">' . $input_end_tag;

        return $output . $error;
    }

    private function _textarea($key, $data_array, $input_class, $value_data, $place_holder, $input_start_tag, $input_end_tag)
    {
        $error = '';
        if (isset($this->errorMessages[$key])) {
            $error = '<span class="invalid-feedback">' . $this->errorMessages[$key][0] . '</span>';
            $input_class = $input_class . ' is-invalid';
        }

        $output = $input_start_tag . '<textarea class="' . $input_class . '" name="settings[' . $key . ']" placeholder="' . $place_holder . '">' . $value_data . '</textarea>' . $input_end_tag;

        return $output . $error;
    }

    private function _image($key, $data_array, $input_class, $value_data, $place_holder, $input_start_tag, $input_end_tag)
    {
        $error = '';
        if (isset($this->errorMessages[$key])) {
            $error = '<span class="invalid-feedback">' . $this->errorMessages[$key][0] . '</span>';
            $input_class = $input_class . ' is-invalid';
        }

        $image = '<div class="fileinput fileinput-new" data-provides="fileinput">
                          <div class="fileinput fileinput-new" data-provides="fileinput">
                              <div class="fileinput-new img-thumbnail lf-w-200px">
                                <img src="' . get_image($value_data) .'"  alt="">
                              </div>
                          <div class="fileinput-preview fileinput-exists img-thumbnail lf-w-200px"></div>
                          <div>
                            <span id="button-group" class="btn btn-sm btn-outline-secondary lf-toggle-bg-input btn-file">
                              <span class="fileinput-new">' . __("Select") . '</span>
                              <span class="fileinput-exists">' . __("Change") . '</span>
                              <input type="file" name="settings[' . $key . ']">
                            </span>
                            <a href="#" id="remove" class="btn btn-sm btn-outline-danger fileinput-exists" data-dismiss="fileinput">' . __("Remove") . '</a>
                          </div>
                          </div>
                   </div>';

        $output = $input_start_tag . $image . $input_end_tag;
        return $output . $error;
    }

    private function _select($key, $data_array, $input_class, $value_data, $place_holder, $input_start_tag, $input_end_tag)
    {
        $error = '';
        if (isset($this->errorMessages[$key])) {
            $error = '<span class="invalid-feedback">' . $this->errorMessages[$key][0] . '</span>';
            $input_class = $input_class . ' is-invalid';
        }

        $output = $input_start_tag . '<div class="lf-select"><select class="' . $input_class . '" name="settings[' . $key . ']">';
        foreach ($data_array as $datakey => $dataval) {
            $output .= '<option value="' . $datakey . '"';
            $output .= $datakey == $value_data ? " selected" : "";
            $output .= '>' . $dataval . '</option>';
        }
        $output .= '</select></div>' . $input_end_tag;
        return $output . $error;
    }

    private function _checkbox($key, $data_array, $input_class, $value_data, $place_holder, $input_start_tag, $input_end_tag)
    {
        $error = '';
        if (isset($this->errorMessages[$key])) {
            $error = '<span class="invalid-feedback">' . $this->errorMessages[$key][0] . '</span>';
            $input_class = $input_class . ' is-invalid';
        }

        $output = '';
        if (is_json($value_data)) {
            $value_data = json_decode($value_data, true);
        }
        $output .= $input_start_tag . '<input id="' . $key . '" type="hidden" name="settings[' . $key . ']" value="">';
        foreach ($data_array as $datakey => $dataval) {
            $output .= '<div class="lf-checkbox"><input id="' . $key . '-' . $datakey . '" class="' . $input_class . '" type="checkbox" name="settings[' . $key . '][]"  value="' . $datakey . '"';
            $output .= is_array($value_data) && in_array($datakey, $value_data) ? " checked" : "";
            $output .= '><label for="' . $key . '-' . $datakey . '">' . $dataval . '</label></div>' . $input_end_tag;
        }
        return $output . $error;
    }

    private function _radio($key, $data_array, $input_class, $value_data, $place_holder, $input_start_tag, $input_end_tag)
    {
        $error = '';
        if (isset($this->errorMessages[$key])) {
            $error = '<span class="invalid-feedback">' . $this->errorMessages[$key][0] . '</span>';
            $input_class = $input_class . ' is-invalid';
        }

        $output = '';
        foreach ($data_array as $datakey => $dataval) {
            $output .= $input_start_tag . '<div class="lf-radio"><input id="' . $key . '-' . $datakey . '" class="' . $input_class . '" type="radio" name="settings[' . $key . ']" value="' . $datakey . '"';
            $output .= $datakey == $value_data ? " checked" : "";
            $output .= '><label for="' . $key . '-' . $datakey . '">' . $dataval . '</label></div>' . $input_end_tag;
        }
        return $output . $error;
    }

    private function _switch($key, $fieldValue, $input_class, $value_data, $place_holder, $input_start_tag, $input_end_tag)
    {
        $error = '';
        if (isset($this->errorMessages[$key])) {
            $error = '<span class="invalid-feedback">' . $this->errorMessages[$key][0] . '</span>';
            $input_class = $input_class . ' is-invalid';
        }

        $output = $input_start_tag . '<div class="lf-switch">';
        $class = 'lf-switch-label lf-switch-label-off';
        $value = '&#10006;';
        foreach ($fieldValue as $dataval) {
            $output .= '<input id="' . $key . '-' . $dataval . '" class="lf-switch-input ' . $input_class . '" type="radio" name="settings[' . $key . ']" value="' . $dataval . '"';
            $output .= $dataval == $value_data ? " checked>" : ">";
            $output .= '<label for="' . $key . '-' . $dataval . '" class="' . $class . '">' . $value . '</label>';
            $class = 'lf-switch-label lf-switch-label-on';
            $value = '&#x2714;';
        }
        $output .= '<span class="lf-switch-selection"></span>' . $input_end_tag . '</div>';
        return $output . $error;
    }
}
