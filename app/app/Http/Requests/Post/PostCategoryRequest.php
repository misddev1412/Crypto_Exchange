<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PostCategoryRequest extends FormRequest
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
        $rules = [
            'is_active' => 'required|in:' . array_to_string(active_status()),
        ];

        $rules['name'] = [
            'required',
            Rule::unique('post_categories', 'name')->ignore($this->route()->parameter('post_category')),
            'max:255'
        ];
        return $rules;
    }
}
