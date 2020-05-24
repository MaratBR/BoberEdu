<?php

namespace App\Http\Controllers;

use App\Http\DTO\CategoriesDto;
use App\Http\DTO\CategoryDto;
use App\Http\DTO\CategoryExDto;
use App\Http\DTO\CourseDto;
use App\Http\DTO\CourseExDto;
use App\Http\DTO\CoursePageItemDto;
use App\Http\DTO\PaginationDto;
use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\Courses\CreateCategoryRequest;
use App\Http\Requests\Courses\CreateNewCourseRequest;
use App\Http\Requests\Courses\SetRateRequest;
use App\Http\Requests\Courses\UpdateCategoryRequest;
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
        $course = $this->courses->getWithOverview($courseId);
        return new CourseExDto($course);
    }

    /**
     * Updates course data
     *
     * @param UpdateCourseRequest $request
     * @param int $courseId
     * @return Response
     */
    public function update(UpdateCourseRequest $request, int $courseId)
    {
        $course = $this->courses->get($courseId);

        $this->courses->update(
            $course,
            $request->getPayload()
        );

        return $this->noContent();
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
        $this->throwErrorIf(500, "Failed to delete course", !$success);

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
        $course = $this->courses->create($request->getPayload());
        return response()->json(new CourseDto($course), 201);
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

    public function category(Request $request, int $categoryId)
    {
        $category = $this->courses->getCategory($categoryId);
        $popular = $this->courses->getPopular($categoryId);
        // $paginator = $this->courses->paginateInCategory($category);
        return new CategoryExDto($category, $popular);
    }

    public function categoryCourses(Request $request, int $categoryId)
    {
        $category = $this->courses->getCategory($categoryId);
        $paginator = $this->courses->paginateInCategory($category);

        return new PaginationDto($paginator, CoursePageItemDto::class);
    }

    public function categories(Request $request)
    {
        $categories = $this->courses->getAllCategories();
        return new CategoriesDto($categories);
    }

    public function getRate(AuthenticatedRequest $request, int $courseId)
    {
        $user = $request->user();
        $rate = $this->courses->getRate();
    }

    public function setRate(SetRateRequest $request, int $courseId)
    {
        $course = $this->courses->get($courseId);

        $this->courses->setRate($course, $request->user(), $request->getValue());

        return $this->noContent();
    }

    public function removeRate(AuthenticatedRequest $request, int $courseId) {
        $course = $this->courses->get($courseId);

        $this->courses->removeRate($course, $request->user());

        return $this->noContent();
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

    public function updateCategory(UpdateCategoryRequest $request, int $categoryId) {
        $category = $this->courses->getCategory($categoryId);
        $d = $request->getPayload();
        $category->update($d);


    }

    public function createCategory(CreateCategoryRequest $request) {
        $d = $request->getPayload();
        $category = $this->courses->createCategory($d);

        return $this->created(new CategoryDto($category));
    }
}

