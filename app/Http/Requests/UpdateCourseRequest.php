<?php

namespace App\Http\Requests;

use App\Course;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::user();
        if ($user == null)
            return false;

        if ($user->isAdmin())
            return true;

        $courseId = $this->route('course');

        return $user != null && $user->teacherAt($courseId);
    }

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
