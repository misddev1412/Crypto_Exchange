<?php

namespace App\Http\Requests\Core;

use Illuminate\Foundation\Http\FormRequest;

class UserStatusRequest extends FormRequest
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
        return [
            'is_email_verified' => 'required|in:' . array_to_string(verified_status()),
            'status' => 'required|in:' . array_to_string(account_status()),
            'is_financial_active' => 'required|in:' . array_to_string(financial_status()),
            'is_accessible_under_maintenance' => 'required|in:' . array_to_string(maintenance_accessible_status()),
        ];
    }

    public function attributes()
    {
        return [
            'is_email_verified' => __('email status'),
            'status' => __('account status'),
            'is_financial_active' => __('financial status'),
            'is_accessible_under_maintenance' => __('maintenace access status'),
        ];
    }
}
