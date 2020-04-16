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
}
