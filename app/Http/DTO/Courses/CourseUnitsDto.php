<?php


namespace App\Http\DTO\Courses;


use App\Course;
use App\Http\DTO\DtoBase;
use App\Http\DTO\UnitExDto;

class CourseUnitsDto extends DtoBase
{
    private $course;

    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    public function getCourseName()
    {
        return $this->course->name;
    }

    public function getCourseId()
    {
        return $this->course->id;
    }

    public function getUnits()
    {
        return collect($this->course->units)->mapInto(UnitExDto::class);
    }
}
