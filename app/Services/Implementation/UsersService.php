<?php


namespace App\Services\Implementation;


use App\Exceptions\ThrowUtils;
use App\Services\Abs\IUsersService;
use App\User;

class UsersService implements IUsersService
{
    use ThrowUtils;


    public function get(int $id): User
    {
        return User::findOrFail($id);
    }

    function getWithRoles(int $id): User
    {
        // It's fine, IDEA, calm down
        /** @var User $user */
        $user = User::with('roles')->findOrFail($id);
        return $user;
    }

    function paginate(int $perPage = 15)
    {
        return User::query()->paginate();
    }

    function create(array $data): User
    {
        return User::create($data);
    }

    function update(User $user, array $data)
    {
        $user->update($data);
    }
}
