<?php


namespace App\Services\Abs;


use App\Course;
use App\Teacher;
use App\TeachingPeriod;
use App\User;
use Carbon\Carbon;

interface ITeachersService
{
    function get(int $id): Teacher;

    function create(User $user, array $data): Teacher;

    function assign(Teacher $teacher, Course $course, Carbon $from, Carbon $to): TeachingPeriod;

    function hasAssignment(Teacher $teacher, Course $course): bool;

    function hasAssignmentDuring(Teacher $teacher, Course $course, Carbon $from, Carbon $to): bool;

    function getAssignment(int $assignmentId): TeachingPeriod;

    function getAssignmentFor(Teacher $teacher, Course $course): TeachingPeriod;
}
