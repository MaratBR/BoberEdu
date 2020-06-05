<?php


namespace App\Http\DTO\Teachers;


use App\Http\DTO\DtoBase;
use App\Teacher;

class TeacherDto extends DtoBase
{
    protected $teacher;

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

    function getAvatar(): ?string
    {
        return $this->teacher->avatar_id === null ? ('https://api.adorable.io/avatars/270/boberTeacher' . $this->getId() . '.png') : $this->teacher->avatar->getRootUrl();
    }
}
