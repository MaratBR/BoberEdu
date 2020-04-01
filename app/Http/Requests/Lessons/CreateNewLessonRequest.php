<?php

namespace App\Http\Requests\Lessons;

use App\Http\Requests\AuthenticatedRequest;

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
