<?php

namespace App\Http\Requests\Core;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TicketRequest extends FormRequest
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
            'title' => 'required|max:255',
            'content' => 'required|max:500',
            'previous_id' => 'nullable|exists:tickets,id',
            'attachment' => 'mimes:jpg,jpeg,png,doc,docx,pdf,txt|max:1024'
        ];
    }

    public function attributes()
    {
        return [
            'content' => 'description',
        ];
    }
}
