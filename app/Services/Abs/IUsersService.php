<?php


namespace App\Services\Abs;


use App\User;

interface IUsersService
{
    function get(int $id): User;

    function update(User $user, array $data);

    function paginate(int $perPage = 15);

    function create(array $data): User;
}
