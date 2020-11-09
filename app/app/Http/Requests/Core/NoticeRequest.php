<?php

namespace App\Http\Requests\Core;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class NoticeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'type' => 'required|in:' . array_to_string(notices_types()),
            'visible_type' => 'required|in:' . array_to_string(notices_visible_types()),
            'start_at' => 'required|date_format:Y-m-d H:i:s',
            'end_at' => 'required|date_format:Y-m-d H:i:s|after:start_at',
            'is_active' => 'required|in:' . array_to_string(active_status())
        ];
    }

    public function attributes()
    {
        return [
            'start_at' => __('start time'),
            'end_at' => __('end time'),
            'is_active' => __('Status')
        ];
    }
}
