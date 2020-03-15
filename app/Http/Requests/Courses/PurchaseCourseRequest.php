<?php

namespace App\Http\Requests\Courses;

use App\Http\Requests\AuthenticatedRequest;
use Illuminate\Foundation\Http\FormRequest;

class PurchaseCourseRequest extends AuthenticatedRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'attendance_id' => 'numeric|required'
        ];
    }

    public function getAttendanceId(): int
    {
        return $this->validated()['attendance_id'];
    }
}
