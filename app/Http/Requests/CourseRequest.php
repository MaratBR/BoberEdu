<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends AuthenticatedRequest
{
    public function getCourseId(): int
    {
        return Utils::asInt($this->route('course'));
    }
}
