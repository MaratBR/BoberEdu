<?php


namespace App\Http\DTO\TeachersDashboard;


use App\Http\DTO\Courses\CourseDto;
use App\Http\DTO\Courses\CourseExDto;
use App\Http\DTO\Courses\CoursePageItemDto;
use App\Http\DTO\DtoBase;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TeacherDashboardDto extends DtoBase
{
    private $income;
    private $popularCourses;

    public function __construct($popularCourses, $income)
    {
        $this->popularCourses = $popularCourses;
        $this->income = $income;
    }

    public function getCourses() {
        return collect($this->popularCourses)->mapInto(CoursePageItemDto::class);
    }

    public function getIncome() {
        return floatval($this->income);
    }
}
