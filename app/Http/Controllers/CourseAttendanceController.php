<?php

namespace App\Http\Controllers;

use App\Http\Requests\Courses\AttendJoinCourseRequest;
use App\Http\Requests\Courses\CourseRequest;
use App\Http\Requests\Courses\PurchaseCourseRequest;
use App\Providers\Services\Abs\ICourseService;
use App\Providers\Services\Abs\IJoinCourseService;

class CourseAttendanceController extends Controller
{
    private $attendances;
    private $courses;

    public function __construct(IJoinCourseService $service, ICourseService $courseService)
    {
        $this->attendances = $service;
        $this->courses = $courseService;
    }

    public function join(CourseRequest $request)
    {
        $course = $this->courses->get($request->getCourseId());

        return $this->attendances->join(
            $course,
            $request->user()
        );
    }

    public function get(CourseRequest $request)
    {
        $course = $this->courses->get($request->getCourseId());
        return $this->attendances->get(
            $course,
            $request->user()
        );
    }

    public function purchase(CourseRequest $request)
    {
        $course = $this->courses->get($request->getCourseId());
        $attendance = $this->attendances->get($course, $request->user());
        return $this->attendances->purchase($attendance, $request->user());
    }
}
