<?php


namespace App\Services\Abs;


use App\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface IUsersService
{
    function get(int $id): User;

    function getBy(string $col, $val): User;

    function update(User $user, array $data);

    function paginate(int $perPage = 15, ?string $order = null);

    function create(array $data): User;

    function userNameTaken(string $username): bool;

    function setAvatar(User $user, \App\FileInfo $avatar);

    function search(string $query): LengthAwarePaginator;
}
