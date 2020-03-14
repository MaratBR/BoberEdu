<?php


namespace App\Providers\Services\Abs;


use App\Lesson;
use App\User;

interface ILessonsService
{
    function get(int $id, User $user): Lesson;
}
