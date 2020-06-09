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

    function getName()
    {
        return $this->user->name;
    }

    public function getAvatar()
    {
        return $this->user->avatar_id !== null ? $this->user->avatar->getRootUrl() : ('https://api.adorable.io/avatars/270/boberUser' . $this->getId() . '.png');
    }

    function getId()
    {
        return $this->user->id;
    }
}
