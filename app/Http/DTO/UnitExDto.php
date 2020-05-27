<?php


namespace App\Http\DTO;


use App\Http\DTO\Lessons\LessonDto;

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
