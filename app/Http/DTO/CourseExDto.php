<?php


namespace App\Http\DTO;


class CourseExDto extends CourseDto
{
    function getUnits()
    {
        return collect($this->course->units)->mapInto(UnitExDto::class);
    }
}
