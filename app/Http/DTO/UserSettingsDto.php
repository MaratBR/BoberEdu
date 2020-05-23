<?php


namespace App\Http\DTO;


use App\User;

class UserSettingsDto extends DtoBase
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    function getId()
    {
        return $this->user->id;
    }

    function getName()
    {
        return $this->user->name;
    }

    function getEmail()
    {
        return $this->user->email;
    }

    function getAbout()
    {
        return $this->user->about;
    }

    function getSex()
    {
        return $this->user->sex;
    }


    public function getAvatar()
    {
        return $this->user->avatar_id !== null ? $this->user->avatar->sys_name : 'default';
    }
}
