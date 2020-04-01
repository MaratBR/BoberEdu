<?php

namespace App\Http\Controllers;

use App\Course;
use App\CourseAttendance;
use App\Http\Requests\Courses\AttendJoinCourseRequest;
use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\Courses\DeleteCourseRequest;
use App\Http\Requests\Courses\CreateNewCourseRequest;
use App\Http\Requests\Courses\UpdateCourseRequest;
use App\Http\Requests\Courses\UpdateCourseUnitsRequest;
use App\Http\Requests\Utils;
use App\Providers\Services\Abs\ICourseService;
use App\Providers\Services\Abs\ICourseUnitsUpdateResponse;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Lanin\Laravel\ApiExceptions\BadRequestApiException;
use Lanin\Laravel\ApiExceptions\ForbiddenApiException;
use Lanin\Laravel\ApiExceptions\InternalServerErrorApiException;

class CourseController extends Controller
{
    private $courses;

    public function __construct(ICourseService $service)
    {
        $this->courses = $service;
    }

    public function show(Request $request)
    {
        return $this->courses->get(
            $request->course,
            true
            );
    }

    public function update(UpdateCourseRequest $request)
    {
        $course = $this->courses->get($request->course);

        $this->courses->update(
            $course,
            $request->validated()
        );
    }

    public function destroy(DeleteCourseRequest $request)
    {
        $course = $this->courses->get($request->course);
        $success = $this->courses->delete($course, $request->isForce());
        if (!$success)
            throw new InternalServerErrorApiException("Failed to delete course");

        return response()->noContent();
    }

    public function store(CreateNewCourseRequest $request)
    {
        $course = $this->courses->create($request->validated());
        return response()->json($course, 201);
    }

    public function index(Request $request)
    {
        return $this->courses->paginate();
    }

    public function updateUnits(UpdateCourseUnitsRequest $request)
    {
        $course = $this->courses->get($request->getCourseId());
        return $this->courses->updateCourseUnits($course, $request);
    }
}
