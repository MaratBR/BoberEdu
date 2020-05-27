<?php


namespace App\Http\DTO\Lessons;


class LessonExDto extends LessonDto
{
    public function getContent()
    {
        return $this->lesson->content;
    }

    public function getUnitName()
    {
        return $this->lesson->unit->name;
    }

    public function getUnitId()
    {
        return $this->lesson->unit->id;
    }

    public function getCourseId()
    {
        return $this->lesson->unit->course->id;
    }

    public function getCourseName()
    {
        return $this->lesson->unit->course->name;
    }
}
