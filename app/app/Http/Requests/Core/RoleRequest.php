<?php

namespace App\Http\Requests\Core;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod('POST')) {
            return [
                'name' => [
                    'required',
                    Rule::unique('roles', 'name'),
                ],
            ];
        }
        return [];
    }


    public function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();
        $validator->after(function () use ($validator) {
            $routeConfigs = config('webpermissions.configurable_routes');
            $roles = $this->get('roles', []);

            foreach ($roles as $roleKey => $roleValue) {
                foreach ($roleValue as $roleGroupKey => $roleGroupValue) {
                    foreach ($roleGroupValue as $key => $role) {
                        if (!isset($routeConfigs[$roleKey][$roleGroupKey][$role])) {
                            unset($roles[$roleKey][$roleGroupKey][$key]);
                        }
                    }
                    if (empty($roles[$roleKey][$roleGroupKey])) {
                        unset($roles[$roleKey][$roleGroupKey]);
                    }
                }
                if (empty($roles[$roleKey])) {
                    unset($roles[$roleKey]);
                }
            }

            $this->merge(['roles' => $roles]);
        });
        return $validator;
    }
}
