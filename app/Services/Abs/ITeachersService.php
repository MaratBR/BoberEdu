<?php


namespace App\Services\Abs;


use App\Course;
use App\Teacher;
use App\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface ITeachersService
{
    function exists(User $user): bool;

    function get(int $id): Teacher;

    function create(User $user, array $data): Teacher;

    function assign(Teacher $teacher, Course $course);

    function revoke(Teacher $teacher, Course $course);

    function hasAssignment(Teacher $teacher, Course $course): bool;

    function paginate(): LengthAwarePaginator;
}
