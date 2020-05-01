<?php


namespace App\Http\DTO;


class LessonExDto extends LessonDto
{
    public function getContent()
    {
        return $this->lesson->content;
    }
}
