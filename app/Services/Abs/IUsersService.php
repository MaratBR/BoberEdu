<?php


namespace App\Services\Abs;


use App\Models\FileInfo;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface IUsersService
{
    function get(int $id): User;

    function getBy(string $col, $val): User;

    function update(User $user, array $data);

    function paginate(int $perPage = 15, ?string $order = null);

    function create(array $data): User;

    function userNameTaken(string $username): bool;

    function setAvatar(User $user, FileInfo $avatar);

    function search(string $query): LengthAwarePaginator;
}
