<?php


namespace App\Services\Abs;


use App\User;

interface IUsersService
{
    function get(int $id): User;

    function update(User $user, array $data);

    function paginate(int $perPage = 15, ?string $order = null);

    function create(array $data): User;

    function userNameTaken(string $username): bool;

    function setAvatar(User $user, \App\FileInfo $avatar);
}
