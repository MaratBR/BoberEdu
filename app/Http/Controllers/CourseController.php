<?php

namespace App\Http\Controllers;

use App\Course;
use App\Http\DTO\CourseDto;
use App\Http\DTO\CourseExDto;
use App\Http\DTO\CoursePageItemDto;
use App\Http\DTO\PaginationDto;
use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\Courses\CreateNewCourseRequest;
use App\Http\Requests\Courses\UpdateCourseRequest;
use App\Http\Requests\Courses\UpdateCourseUnitsRequest;
use App\Services\Abs\ICourseService;
use App\Services\Abs\ICourseUnitsUpdateResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Lanin\Laravel\ApiExceptions\InternalServerErrorApiException;

class CourseController extends Controller
{
    private $courses;

    public function __construct(ICourseService $service)
    {
        $this->courses = $service;
    }

    /**
     * Returns a course with given id, also includes some data such as units and list of lessons
     *
     * @param int $courseId
     * @return CourseDto
     */
    public function show(int $courseId)
    {

        $course = $this->courses->getWithUnitsAndLessonsNames(
            $courseId
        );
        return new CourseExDto($course);

    }

    /**
     * Updates course data
     *
     * @param UpdateCourseRequest $request
     * @param int $courseId
     */
    public function update(UpdateCourseRequest $request, int $courseId)
    {
        $course = $this->courses->get($courseId);

        $this->courses->update(
            $course,
            $request->validated()
        );
    }

    /**
     * Deletes course
     *
     * @param AuthenticatedRequest $request
     * @param int $courseId
     * @return Response 204 No Content
     */
    public function destroy(AuthenticatedRequest $request, int $courseId)
    {
        $course = $this->courses->get($courseId);
        $success = $this->courses->delete($course);
        if (!$success)
            throw new InternalServerErrorApiException("Failed to delete course");

        return response()->noContent();
    }

    /**
     * Creates new course
     *
     * @param CreateNewCourseRequest $request
     * @return JsonResponse 201 Created
     */
    public function store(CreateNewCourseRequest $request)
    {
        $course = $this->courses->create($request->validated());
        return response()->json($course, 201);
    }

    /**
     * Returns list of course and also some useful info such as count of units and lessons
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $paginator = $this->courses->paginateWithExtra();
        return new PaginationDto($paginator, CoursePageItemDto::class);
    }

    /**
     * Updates course units
     *
     * @param UpdateCourseUnitsRequest $request
     * @param int $courseId
     * @return ICourseUnitsUpdateResponse
     */
    public function updateUnits(UpdateCourseUnitsRequest $request, int $courseId)
    {
        $course = $this->courses->get($courseId);
        return $this->courses->updateCourseUnits($course, $request);
    }

    /**
     * Returns list of units with corresponding lessons
     *
     * @param int $courseId
     * @return array
     */
    public function lessons(int $courseId)
    {
        $course = $this->courses->getWithUnitsAndLessonsNames($courseId);
        return [
            'units' => $this->createUnitsWithLessonsNamesDto($course)
        ];
    }

    /**
     * Returns list of units w/o lessons
     *
     * @param int $courseId
     * @return array
     */
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
}

