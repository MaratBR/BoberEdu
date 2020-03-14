<?php


namespace App\Providers\Services\Abs;


use App\User;

interface IUsersService
{
    function get(int $id): User;
    function paginate(int $perPage = 15);
    function create(array $data): User;
    // function getByUsername(string $username): User;
}
