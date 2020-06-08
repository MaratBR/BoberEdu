<?php

namespace App\Http\Controllers;

use App\Http\DTO\Courses\CoursePageItemDto;
use App\Http\DTO\PaginationDto;
use App\Http\Requests\SearchRequest;
use App\Services\Abs\ICourseService;
use App\Services\Abs\ITeachersService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(SearchRequest $request, ICourseService $courseService, ITeachersService $teachersService)
    {
        $q = $request->getQuery();

        if (!$q || trim($q) === '')
            return new PaginationDto([]);

        $q = trim($q);
        return new PaginationDto($courseService->search($q, null), CoursePageItemDto::class);
    }
}
