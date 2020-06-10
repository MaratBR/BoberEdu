<?php


namespace App\Http\DTO\Users;


use App\Http\DTO\DtoBase;
use App\Role;
use App\User;
use Carbon\Carbon;

class UserDto extends DtoBase
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getAvatar()
    {
        return $this->user->avatar_id !== null ? $this->user->avatar->getRootUrl() : ('https://api.adorable.io/avatars/270/boberUser' . $this->getId() . '.png');
    }

    public function getId(): int
    {
        return $this->user->id;
    }

    public function getAbout()
    {
        return $this->user->about;
    }

    public function getJoinedAt(): Carbon
    {
        return $this->user->created_at;
    }

    public function getName(): string
    {
        return $this->user->name;
    }

    public function getStatus(): ?string
    {
        return $this->user->status;
    }

    public function isAdmin()
    {
        return $this->user->is_admin;
    }
}
