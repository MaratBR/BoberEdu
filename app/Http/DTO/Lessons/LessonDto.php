<?php


namespace App\Http\DTO\Lessons;


use App\Http\DTO\DtoBase;
use App\Lesson;

class LessonDto extends DtoBase
{
    protected $lesson;

    public function __construct(Lesson $lesson)
    {
        $this->lesson = $lesson;
    }

    public function getTitle()
    {
        return $this->lesson->title;
    }

    public function getSummary()
    {
        return $this->lesson->summary;
    }

    public function getId()
    {
        return $this->lesson->id;
    }
}
