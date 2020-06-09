<?php


namespace App\Http\DTO\Courses;


use App\Http\DTO\Teachers\TeacherDto;
use App\Http\DTO\Units\UnitExDto;

class CourseExDto extends CourseDto
{
    function getUnits()
    {
        return collect($this->course->units)->mapInto(UnitExDto::class);
    }

    function getCategory()
    {
        return [
            'id' => $this->course->category->id,
            'name' => $this->course->category->name,
        ];
    }

    public function getTeachers()
    {
        return collect($this->course->teachers)->mapInto(TeacherDto::class);
    }
}
