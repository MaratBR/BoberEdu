<?php

namespace App\Http\Controllers\Admin;

use App\AuditRecord;
use App\Http\Controllers\Controller;
use App\Http\DTO\Courses\CourseExDto;
use App\Http\DTO\Courses\CoursePageItemDto;
use App\Http\DTO\PaginationDto;
use App\Http\DTO\Units\StandaloneUnitDto;
use App\Http\DTO\Units\UnitDto;
use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\Courses\UpdateCourseRequest;
use App\Http\Requests\SearchRequest;
use App\Services\Abs\ICourseService;
use App\Utils\Audit\Audit;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
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

    private const COURSE_SEARCH_FIELDS = ['id', 'name'];
    public function search(SearchRequest $request)
    {
        if ($request->getQuery() === null) {
            return new PaginationDto($this->repo->paginateWithExtra(), CoursePageItemDto::class);
        }

        if ($request->hasParametricSearch() &&
            in_array($request->getParameter(), self::COURSE_SEARCH_FIELDS)) {
            $result = [$this->repo->getBy($request->getParameter(), $request->getQuery())];
        } else {
            $result = $this->repo->search($request->getQuery());
        }
        return new PaginationDto($result, CoursePageItemDto::class);
    }
}
