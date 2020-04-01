<?php


namespace App\Providers\Services\Implementation;


use App\Providers\Services\Abs\IUsersService;
use App\User;

class UsersService implements IUsersService
{

    function get(int $id): User
    {
        // It's fine, IDEA, calm down
        return User::with('roles')->findOrFail($id);
    }

    function paginate(int $perPage = 15)
    {
        return User::query()->paginate();
    }

    function create(array $data): User
    {
        return User::create($data);
    }
}
