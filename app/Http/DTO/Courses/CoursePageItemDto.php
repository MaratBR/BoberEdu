<?php


namespace App\Http\DTO\Courses;


class CoursePageItemDto extends CourseDto
{
    public function getInfo()
    {
        return [
            'lc' => $this->course->lessons_count,
            'uc' => $this->course->units_count
        ];
    }
}
