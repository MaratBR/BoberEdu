<?php


namespace App\Http\DTO\Teachers;


use App\Http\DTO\Users\UserDto;

class AdminTeacherDto extends TeacherDto
{
    public function getDocId()
    {
        return $this->teacher->passport_num;
    }

    public function getUser()
    {
        return new UserDto($this->teacher->user);
    }
}
