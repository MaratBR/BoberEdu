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

    function getDisplayName()
    {
        return $this->user->display_name ? $this->user->display_name : $this->getName();
    }


    public function getAvatar()
    {
        return $this->user->avatar_id !== null ? $this->user->avatar->getRootUrl() : 'default';
    }
}
