<?php

namespace App\Http\Requests\Teachers;

use App\Http\Requests\AuthenticatedRequest;
use App\Models\Teacher;
use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends AuthenticatedRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return parent::authorize() && $this->user()->hasOne(Teacher::class);
    }

    public function teacher(): Teacher
    {
        return $this->user()->teacher;
    }
}
