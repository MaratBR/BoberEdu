<?php


namespace App\Services\Abs;


use App\Course;
use App\Teacher;
use App\TeacherApprovalForm;
use App\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Laravel\Scout\Builder;

interface ITeachersService
{
    function exists(User $user): bool;

    function get(int $id): Teacher;

    function create(User $user, array $data): Teacher;

    function assign(Teacher $teacher, Course $course);

    function revoke(Teacher $teacher, Course $course);

    function paginateApprovalForms(?bool $approved = null): LengthAwarePaginator;

    function getApprovalForm(int $id): TeacherApprovalForm;

    function getLastApprovalForm(User $user): TeacherApprovalForm;

    function createApprovalForm(User $user, array $data): TeacherApprovalForm;

    /**
     * @param $teacher Teacher|int
     * @param $course Course|int
     * @return bool
     */
    function hasAssignment($teacher, $course): bool;

    function paginate(): LengthAwarePaginator;

    function search(string $query): Builder;
}
