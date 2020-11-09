<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
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
            'title' => [
                'required',
                Rule::unique('posts', 'title')->ignore($this->route()->parameter('post')),
                'max:255'
            ],
            'category_slug' => [
                'required',
                Rule::exists('post_categories', 'slug'),
            ],
            'editor_content' => [
                'required',
            ],
            'featured_image' => [
                'image',
                'max:2048',
            ],
            'is_published' => [
                'required',
                Rule::in(array_keys(active_status())),
            ],
            'is_featured' => [
                'required',
                Rule::in(array_keys(active_status())),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'editor_content' => __('content')
        ];
    }
}
