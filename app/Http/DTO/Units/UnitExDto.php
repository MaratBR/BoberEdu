<?php


namespace App\Http\DTO\Units;


use App\Http\DTO\Lessons\LessonDto;
use App\Http\DTO\Units\UnitDto;

class UnitExDto extends UnitDto
{
    public function getLessons()
    {
        return collect($this->unit->lessons)->mapInto(LessonDto::class);
    }

    public function getAbout()
    {
        return $this->unit->about;
    }
}
