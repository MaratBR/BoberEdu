<?php


namespace App\Http\DTO;


use App\Role;
use App\User;
use Carbon\Carbon;

class UserDto extends DtoBase
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getId(): int
    {
        return $this->user->id;
    }

    public function getAvatar()
    {
        return $this->user->avatar_id !== null ? $this->user->avatar->sys_name : 'default';
    }

    public function getAbout()
    {
        return $this->user->about;
    }

    public function getJoinedAt(): Carbon
    {
        return $this->user->created_at;
    }

    public function getDisplayName(): ?string
    {
        return $this->user->display_name;
    }

    public function getName(): string
    {
        return $this->user->name;
    }

    public function getStatus(): ?string
    {
        return $this->user->status;
    }

    public function getRoles()
    {
        return collect($this->user->roles)->map(function (Role $r) {
            return $r->name;
        });
    }
}
