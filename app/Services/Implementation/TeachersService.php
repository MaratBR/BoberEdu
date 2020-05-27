<?php


namespace App\Services\Implementation;


use App\Course;
use App\Services\Abs\ITeachersService;
use App\Teacher;
use App\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

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

    function hasAssignment(Teacher $teacher, Course $course): bool
    {
        return DB::table('teaching_assignments')
            ->where('teacher_id', '=', $teacher->id)
            ->where('course_id', '=', $course->id)
            ->exists();
    }

    function revoke(Teacher $teacher, Course $course)
    {
        DB::table('teaching_assignments')
            ->where('teacher_id', '=', $teacher->id)
            ->where('course_id', '=', $course->id)
            ->delete();
    }

    function paginate(): LengthAwarePaginator
    {
        return Teacher::query()->paginate();
    }
}
