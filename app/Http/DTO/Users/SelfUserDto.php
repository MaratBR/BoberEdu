<?php


namespace App\Http\DTO\Users;


class SelfUserDto extends UserDto
{
    public function getIsTeacher()
    {
        return $this->user->teacher()->exists();
    }
}
