<?php


namespace App\Services\Implementation;


use App\Services\Abs\ITeachersService;
use App\Teacher;
use App\User;

class TeachersService implements ITeachersService
{

    function get(int $id): Teacher
    {
        return Teacher::findOrFail($id);
    }

    function create(User $user, array $data): Teacher
    {
        $data['user_id'] = $user->id;

        return Teacher::create($data);
    }
}
