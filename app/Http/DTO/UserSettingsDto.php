<?php


namespace App\Http\DTO;


use App\Models\User;

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
