<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseUnitsRequest extends UpdateCourseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return parent::authorize() or true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'delete.*' => 'numeric',

            'order.*' => ['regex:/^n?(?:0|[1-9]\d*)$/i'],

            'new.*.name' => 'required|string',
            'new.*.about' => 'required|string',
            'new.*.is_preview' => 'boolean',

            'upd.*.id' => 'numeric|required',
            'upd.*.name' => 'string',
            'upd.*.about' => 'string',
            'upd.*.is_preview' => 'boolean',
        ];
    }
}
