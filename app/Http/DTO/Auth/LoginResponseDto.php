<?php


namespace App\Http\DTO\Auth;


use App\Http\DTO\DtoBase;
use App\User;

class LoginResponseDto extends DtoBase
{
    public $token;
    private $user;

    public function __construct(string $token, User $user)
    {
        $this->user = $user;
        $this->token = $token;
    }
}
