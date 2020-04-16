<?php


namespace App\Services\Abs;


use App\Teacher;
use App\User;

interface ITeachersService
{
    function get(int $id): Teacher;

    function create(User $user, array $data): Teacher;
}
