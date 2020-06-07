<?php


namespace App\Http\DTO\Teachers;


use App\Http\DTO\Users\UserDto;

class AdminTeacherDto extends TeacherExDto
{
    public function getUser()
    {
        return new UserDto($this->teacher->user);
    }
}
