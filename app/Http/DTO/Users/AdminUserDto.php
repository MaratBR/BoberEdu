<?php


namespace App\Http\DTO\Users;


use App\Http\DTO\Teachers\AdminTeacherDto;

class AdminUserDto extends UserDto
{
    public function getTeacher()
    {
        return $this->user->teacher === null ? null : new AdminTeacherDto($this->user->teacher);
    }
}
