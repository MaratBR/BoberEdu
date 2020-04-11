<?php


namespace App\Http\DTO;


class UnitExDto extends UnitDto
{
    public function getLessons()
    {
        return collect($this->unit->lessons)->mapInto(LessonDto::class);
    }
}
