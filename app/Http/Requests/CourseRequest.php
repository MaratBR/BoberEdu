<?php

namespace App\Http\Requests;

class CourseRequest extends AuthenticatedRequest
{
    public function getCourseId(): int
    {
        return Utils::asInt($this->route('course'));
    }
}
