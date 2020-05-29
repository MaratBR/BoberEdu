<?php


namespace App\Http\DTO\Units;


class StandaloneUnitDto extends UnitDto
{
    public function getCourseId()
    {
        return $this->unit->course_id;
    }

    public function getCourseName()
    {
        return $this->unit->course->name;
    }
}
