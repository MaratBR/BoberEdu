<?php

namespace App\Http\Requests;

use App\Course;
use App\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateNewCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        $user = $this->user();
        return $user != null &&
            $user->can('create', Course::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:1|max:255',
            'price' => 'numeric|min:0|max:9999999999999999999.99',
            'about' => 'required',
            'sign_up_beg' => 'nullable|date_format:Y-m-d',
            'sign_up_end' => 'nullable|date_format:Y-m-d',
            'available' => 'boolean'
        ];
    }
}
