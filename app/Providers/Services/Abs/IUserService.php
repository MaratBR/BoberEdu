<?php


namespace App\Providers\Services\Abs;


use App\User;

interface IUserService
{
    function get(int $id): User;


}
