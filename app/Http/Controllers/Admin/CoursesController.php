<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\DTO\Units\StandaloneUnitDto;
use App\Http\DTO\Units\UnitDto;
use App\Services\Abs\ICourseService;
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
}
