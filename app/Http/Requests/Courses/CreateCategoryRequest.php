<?php

namespace App\Http\Requests\Courses;

use App\Http\Requests\AdminRequest;
use App\Http\Requests\IPayloadRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends UpdateCategoryRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string|required|max:40',
            'about' => 'string|required|max:250',
            'color' => 'string|max:6|min:6'
        ];
    }
}
