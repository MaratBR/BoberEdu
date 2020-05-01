<?php


namespace App\Http\DTO;


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

    public function getId()
    {
        return $this->lesson->id;
    }
}
