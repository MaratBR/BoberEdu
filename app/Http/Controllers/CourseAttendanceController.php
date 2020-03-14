<?php

namespace App\Http\Controllers;

use App\CourseAttendance;
use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\Courses\AttendCourseRequest;
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

    public function attend(AttendCourseRequest $request)
    {
        $course = $this->courses->get($request->getCourseId());
        $this->authorize('buy', $course);

        if (!$course->canBePurchased())
            throw new BadRequestApiException("This course cannot be purchased, hence you cannot attend it");

        return $this->attendances->attend(
            $course,
            $request->user(),
            $request
        );
    }

    public function status(AuthenticatedRequest $request)
    {
        $courseId = $request->course;
        $status = $this->attendances->attendanceStatus(
            $courseId,
            $request->user()->id
        );
        if (!$status->courseExists())
            throw new ModelNotFoundException("Course with id = $courseId not found");

        return [
            'status' => $status->asString()
        ];
    }
}
