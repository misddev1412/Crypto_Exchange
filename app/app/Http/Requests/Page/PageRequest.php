<?php

namespace App\Http\Requests\Page;

use App\Models\Page\Page;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
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
            'editor_content' => 'required',
            'meta_keys.*' => 'required|string',
            'meta_description' => 'nullable|max:160',
            'is_published' => 'required|in:' . array_to_string(active_status()),
        ];
        $rules['title'] = [
            'required',
            Rule::unique('pages', 'title')->ignore($this->route('page')),
            'max:255'
        ];

        return $rules;
    }
}
