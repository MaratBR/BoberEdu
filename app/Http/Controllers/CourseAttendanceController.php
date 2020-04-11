<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\Courses\CourseRequest;
use App\Services\Abs\ICourseService;
use App\Services\Abs\IUserCoursesService;

class CourseAttendanceController extends Controller
{
    private $attendances;
    private $courses;

    public function __construct(IUserCoursesService $service, ICourseService $courseService)
    {
        $this->attendances = $service;
        $this->courses = $courseService;
    }

    public function join(AuthenticatedRequest $request, int $courseId)
    {
        $course = $this->courses->get($courseId);

        return $this->attendances->join(
            $course,
            $request->user()
        );
    }

    public function get(AuthenticatedRequest $request, int $courseId)
    {
        $course = $this->courses->get($courseId);
        return $this->attendances->get(
            $course,
            $request->user()
        );
    }
}
