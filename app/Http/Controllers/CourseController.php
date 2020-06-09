<?php

namespace App\Http\Controllers;

use App\Http\DTO\Categories\CategoriesDto;
use App\Http\DTO\Categories\CategoryDto;
use App\Http\DTO\Categories\CategoryExDto;
use App\Http\DTO\Courses\CourseDto;
use App\Http\DTO\Courses\CourseExDto;
use App\Http\DTO\Courses\CoursePageItemDto;
use App\Http\DTO\Courses\CourseUnitsDto;
use App\Http\DTO\PaginationDto;
use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\Courses\CreateCategoryRequest;
use App\Http\Requests\Courses\OrdnungMussSeinRequest;
use App\Http\Requests\Courses\SetRateRequest;
use App\Http\Requests\Courses\UpdateCategoryRequest;
use App\Services\Abs\ICourseService;
use Illuminate\Http\Request;
use Lanin\Laravel\ApiExceptions\InternalServerErrorApiException;
use function response;

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

    public function getUnits(int $courseId)
    {
        $course = $this->courses->getWithOverview($courseId);
        return new CourseUnitsDto($course);
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
        return new CategoryExDto($category);
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
        return response()->json($this->courses->getRate($request->user(), $courseId));
    }

    public function setRate(SetRateRequest $request, int $courseId)
    {
        $course = $this->courses->get($courseId);

        $this->courses->setRate($course, $request->user(), $request->getValue());

        return $this->noContent();
    }

    public function removeRate(AuthenticatedRequest $request, int $courseId)
    {
        $course = $this->courses->get($courseId);

        $this->courses->removeRate($course, $request->user());

        return $this->noContent();
    }

    public function updateLessonsOrder(OrdnungMussSeinRequest $request, int $courseId)
    {
        $this->courses->putLessonsOrder($courseId, $request->getPayload());
    }

    public function updateCategory(UpdateCategoryRequest $request, int $categoryId)
    {
        $category = $this->courses->getCategory($categoryId);
        $d = $request->getPayload();
        $category->update($d);
    }

    public function createCategory(CreateCategoryRequest $request)
    {
        $d = $request->getPayload();
        $category = $this->courses->createCategory($d);

        return $this->created(new CategoryDto($category));
    }
}

