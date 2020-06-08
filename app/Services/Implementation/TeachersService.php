<?php


namespace App\Services\Implementation;


use App\Course;
use App\Services\Abs\ITeachersService;
use App\Teacher;
use App\TeacherApprovalForm;
use App\User;
use App\Utils\Convert;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Builder;

class TeachersService implements ITeachersService
{
    function exists(User $user): bool
    {
        return Teacher::query()->where('user_id', '=', $user->id)->exists();
    }

    function get(int $id): Teacher
    {
        return Teacher::findOrFail($id);
    }

    function create(User $user, array $data): Teacher
    {
        $data['user_id'] = $user->id;

        return Teacher::create($data);
    }

    function assign(Teacher $teacher, Course $course)
    {
        $course->teachers()->attach($teacher->id);
    }

    function hasAssignment($teacher, $course): bool
    {
        $teacher = $teacher instanceof Teacher ? $teacher->id : $teacher;
        $course = $course instanceof Course ? $course->id : $course;

        return DB::table('teaching_assignments')
            ->where('teacher_id', '=', $teacher)
            ->where('course_id', '=', $course)
            ->exists();
    }

    function revoke(Teacher $teacher, Course $course)
    {
        DB::table('teaching_assignments')
            ->where('teacher_id', '=', $teacher->id)
            ->where('course_id', '=', $course->id)
            ->delete();
    }

    function paginateApprovalForms(?bool $approved = null): LengthAwarePaginator
    {
        $q = TeacherApprovalForm::query();

        if ($approved !== null) {
            $q = $q->where('approved', '=', $approved);
        }

        return $q->paginate();
    }

    function getApprovalForm(int $id): TeacherApprovalForm
    {
        return TeacherApprovalForm::findOrFail($id);
    }

    function createApprovalForm(array $data): TeacherApprovalForm
    {
        return TeacherApprovalForm::create($data);
    }

    function paginate(): LengthAwarePaginator
    {
        return Teacher::query()->paginate();
    }

    function search(string $query): Builder
    {
        return Teacher::search(Convert::escapeElasticReservedChars($query));
    }
}
