<?php

namespace App\Http\Controllers;

use App\Course;
use App\Http\Requests\Courses\CreateNewCourseRequest;
use App\Http\Requests\Courses\DeleteCourseRequest;
use App\Http\Requests\Courses\UpdateCourseRequest;
use App\Http\Requests\Courses\UpdateCourseUnitsRequest;
use App\Services\Abs\ICourseService;
use Illuminate\Http\Request;
use Lanin\Laravel\ApiExceptions\InternalServerErrorApiException;

class CourseController extends Controller
{
    private $courses;

    public function __construct(ICourseService $service)
    {
        $this->courses = $service;
    }

    public function show(int $courseId)
    {
        $course = $this->courses->getWithUnitsAndLessonsNames(
            $courseId
        );
        return $this->createCourseDto($course);
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

    public function updateUnits(UpdateCourseUnitsRequest $request, int $courseId)
    {
        $course = $this->courses->get($courseId);
        return $this->courses->updateCourseUnits($course, $request);
    }

    public function lessons(int $courseId)
    {
        $course = $this->courses->getWithUnitsAndLessonsNames($courseId);
        return [
            'units' => $this->createUnitsWithLessonsNamesDto($course)
        ];
    }

    public function units(int $courseId)
    {
        $course = $this->courses->getWithUnits($courseId);
        $units = $this->createUnitsDto($course);
        return [
            'units' => $units
        ];
    }

    private function createUnitsDto(Course $course)
    {
        return $course->units;
    }

    private function createUnitsWithLessonsNamesDto(Course $course)
    {
        return collect($course->units)->map(function ($unit) {
            return [
                'name' => $unit->name,
                'is_preview' => $unit->is_preview,
                'about' => $unit->about,
                'lessons' => collect($unit->lessons)->map(function ($lesson) {
                    return $lesson->title;
                })
            ];
        });
    }

    private function createCourseDto(Course $course)
    {
        $data = collect($course);

        $data['units'] = $this->createUnitsWithLessonsNamesDto($course);
        return $data;
    }
}

