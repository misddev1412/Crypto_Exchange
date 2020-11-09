<?php

namespace App\Http\Requests\Core;

use Illuminate\Foundation\Http\FormRequest;

class NavigationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $formData['menu_item'] = json_decode($this->formData,true);
        $this->request->remove('formData');
        $this->request->add($formData);
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
            'menu_item.*.name'=>'present',
            'menu_item.*.class'=>'present',
            'menu_item.*.icon'=>'present',
            'menu_item.*.beginning_text'=>'present',
            'menu_item.*.ending_text'=>'present',
            'menu_item.*.custom_link'=>'present',
            'menu_item.*.route'=>'present',
            'menu_item.*.parent_id'=>'required|integer|min:0',
            'menu_item.*.new_tab'=>'required|in:0,1',
            'menu_item.*.mega_menu'=>'required|in:0,1',
            'menu_item.*.order'=>'required|integer|min:1',
        ];
    }
}
