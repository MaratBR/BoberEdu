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

    function getAvatar()
    {
        $id = $this->user->avatar ? $this->user->avatar : 'default';
        return $id;
    }
}
