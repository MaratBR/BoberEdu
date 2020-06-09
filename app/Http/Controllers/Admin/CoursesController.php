<?php

namespace App\Http\Controllers\Admin;

use App\AuditRecord;
use App\Http\Controllers\Controller;
use App\Http\DTO\Courses\CourseExDto;
use App\Http\DTO\Courses\CoursePageItemDto;
use App\Http\DTO\PaginationDto;
use App\Http\DTO\Units\StandaloneUnitDto;
use App\Http\DTO\Uploads\UploadedDto;
use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\Courses\UpdateCourseRequest;
use App\Http\Requests\Courses\UpdateCourseUnitsRequest;
use App\Http\Requests\SearchRequest;
use App\Services\Abs\ICourseService;
use App\Services\Abs\IUploadService;
use App\Utils\Audit\Audit;
use Illuminate\Http\JsonResponse;

class CoursesController extends Controller
{
    private const COURSE_SEARCH_FIELDS = ['id', 'name'];
    private $repo;

    public function __construct(ICourseService $courses)
    {
        $this->repo = $courses;
    }

    public function unit(int $unitId)
    {
        return new StandaloneUnitDto($this->repo->getUnit($unitId));
    }

    public function get(int $id)
    {
        return new CourseExDto($this->repo->get($id));
    }

    public function update(UpdateCourseRequest $request, int $id)
    {
        $course = $this->repo->get($id);

        $course->update($request->getPayload());
        AuditRecord::make($request->user(), $request, Audit::UPDATE)
            ->subject($course)->build();

        return new CourseExDto($course);
    }

    public function search(SearchRequest $request)
    {
        $categoryId = $request->input('c');
        $categoryId = !is_string($categoryId) ? null : intval($categoryId, 10);
        $categoryId = $categoryId === 0 ? null : $categoryId;

        if ($request->getQuery() === null) {
            if ($categoryId === null) {
                return new PaginationDto($this->repo->paginateWithExtra(), CoursePageItemDto::class);
            } else {
                $category = $this->repo->getCategory($categoryId);
                return new PaginationDto($this->repo->paginateInCategory($category), CoursePageItemDto::class);
            }
        }

        if ($request->hasParametricSearch() &&
            in_array($request->getParameter(), self::COURSE_SEARCH_FIELDS)) {
            $params = [
                $request->getParameter() => $request->getQuery()
            ];

            if ($categoryId !== null)
                $params['category_id'] = $categoryId;
            $result = [$this->repo->getBy($params)];
        } else {
            $result = $this->repo->search($request->getQuery());
        }
        return new PaginationDto($result, CoursePageItemDto::class);
    }

    public function uploadImage(AuthenticatedRequest $request, int $courseId, IUploadService $uploadService)
    {
        $course = $this->repo->get($courseId);
        $file = $this->openInput();

        $fileInfo = $uploadService->uploadImage($request->user(), 'course_image', $file);
        $course->update(['image_id' => $fileInfo->id]);

        return new UploadedDto($fileInfo);
    }

    /**
     * Updates course units
     *
     * @param UpdateCourseUnitsRequest $request
     * @param int $courseId
     * @return JsonResponse
     */
    public function updateUnits(UpdateCourseUnitsRequest $request, int $courseId)
    {
        $course = $this->repo->get($courseId);
        $this->repo->updateCourseUnits($course, $request);

        AuditRecord::make($request->user(), $request, Audit::UPDATE_COURSE_UNITS)
            ->subject($course)->build();

        return $this->done();
    }
}
