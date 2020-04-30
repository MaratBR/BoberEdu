<?php


namespace App\Http\DTO;


use App\Teacher;

class TeacherDto extends DtoBase
{
    private $teacher;

    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    function getId(): int
    {
        return $this->teacher->id;
    }

    function getFullName(): string
    {
        return $this->teacher->full_name;
    }

    function getAvatar(): string
    {
        return "5f6b0239cfb94072cfe42b790c63b7db";
    }
}
