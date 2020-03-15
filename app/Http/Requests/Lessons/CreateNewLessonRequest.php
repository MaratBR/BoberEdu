<?php

namespace App\Http\Requests\Lessons;

use App\Http\Requests\AuthenticatedRequest;
use App\Lesson;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreateNewLessonRequest extends AuthenticatedRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'unit_id' => 'numeric|required',
            'title' => 'string|required',
            'content' => 'string',
            'order_num' => 'numeric'
        ];
    }
}
