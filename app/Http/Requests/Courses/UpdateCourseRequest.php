<?php

namespace App\Http\Requests\Courses;

class UpdateCourseRequest extends CourseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'min:1|max:255',
            'price' => 'numeric|min:0|max:9999999999999999999.99',
            'about' => 'string',
            'sign_up_beg' => 'nullable|date_format:Y-m-d',
            'sign_up_end' => 'nullable|date_format:Y-m-d',
            'available' => 'boolean'
        ];
    }
}
