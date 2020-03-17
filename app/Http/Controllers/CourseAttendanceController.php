<?php

namespace App\Http\Controllers;

use App\CourseAttendance;
use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\Courses\AttendCourseRequest;
use App\Http\Requests\Courses\PurchaseCourseRequest;
use App\Providers\Services\Abs\IAttendanceStatus;
use App\Providers\Services\Abs\ICourseAttendanceService;
use App\Providers\Services\Abs\ICourseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Lanin\Laravel\ApiExceptions\BadRequestApiException;

class CourseAttendanceController extends Controller
{
    private $attendances;
    private $courses;

    public function __construct(ICourseAttendanceService $service, ICourseService $courseService)
    {
        $this->attendances = $service;
        $this->courses = $courseService;
    }

    /**
     * Handles request to attend a course.
     * When user make such request it means user wants to attend this course and either want to start trial period or
     * wants to pay for it. Associated purchase record won't be created. User need to do that manually.
     *
     * @param AttendCourseRequest $request
     * @return CourseAttendance
     */
    public function attend(AttendCourseRequest $request)
    {
        $course = $this->courses->get($request->getCourseId());

        return $this->attendances->attend(
            $course,
            $request->user(),
            $request
        );
    }

    public function get(AuthenticatedRequest $request)
    {
        return $this->attendances->get(
            $request->course,
            $request->user()
        );
    }

    /**
     * Returns a status of attendance to the user.
     *
     * @param AuthenticatedRequest $request
     * @return IAttendanceStatus
     */
    public function status(AuthenticatedRequest $request)
    {
        $courseId = $request->course;
        return $this->attendances->attendanceStatus($courseId, $request->user());
    }

    public function submit(PurchaseCourseRequest $request)
    {
        $attendance = $this->attendances->get($request->course, $request->user());
        return $this->attendances->makePurchase($attendance, $request->user());
    }
}
