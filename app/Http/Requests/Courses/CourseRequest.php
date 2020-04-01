<?php

namespace App\Http\Requests\Courses;

use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\Utils;

class CourseRequest extends AuthenticatedRequest
{
    function getCourseId(): int
    {
        return Utils::asInt($this->route('course'));
    }
}
