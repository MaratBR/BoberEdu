<?php


namespace App\Http\DTO;


use App\Role;
use App\User;
use Carbon\Carbon;
use function Clue\StreamFilter\fun;

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

    public function getJoinedAt(): Carbon
    {
        return $this->user->created_at;
    }

    public function getSex(): string
    {
        return $this->user->sex;
    }

    public function getName(): string
    {
        return $this->user->name;
    }

    public function getRoles()
    {
        return collect($this->user->roles)->map(function (Role $r) {
            return $r->name;
        });
    }
}
