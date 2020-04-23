<?php


namespace App\Services\Implementation;


use App\Course;
use App\Services\Abs\ITeachersService;
use App\Teacher;
use App\TeachingPeriod;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

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

    function assign(Teacher $teacher, Course $course, Carbon $from, Carbon $to): TeachingPeriod
    {
        return TeachingPeriod::create([
            'since' => $from,
            'until' => $to,
            'teacher_id' => $teacher->id,
            'course_id' => $course->id
        ]);
    }

    private function teacherAssignmentsQuery(Teacher $teacher, Course $course): Builder
    {
        return TeachingPeriod::query()
            ->where('teacher_id', '=', $teacher->id)
            ->where('course_id', '=', $course->id);
    }

    private function currentTeacherAssignmentQuery(Teacher $teacher, Course $course): Builder
    {
        return $this->teacherAssignmentsQuery($teacher, $course)
            ->where(function (Builder $q) {
                $q->where(function (Builder $q) {
                    $q->where('since', '=', null);
                    $q->where('until', '=', null);
                });
                $q->orWhere(function (Builder $q) {
                    $now = Carbon::now('UTC');
                    $q->orWhere('since', '>=', $now);
                    $q->where('until', '<=', $now);
                });
            });
    }

    function hasAssignment(Teacher $teacher, Course $course): bool
    {
        return $this->currentTeacherAssignmentQuery($teacher, $course)->exists();
    }

    function getAssignment(int $assignmentId): TeachingPeriod
    {
        return TeachingPeriod::findOrFail($assignmentId);
    }

    function getAssignmentFor(Teacher $teacher, Course $course): TeachingPeriod
    {
        return $this->currentTeacherAssignmentQuery()->firstOrFail();
    }

    function hasAssignmentDuring(Teacher $teacher, Course $course, Carbon $from, Carbon $to): bool
    {
        return $this->teacherAssignmentsQuery($teacher, $course)
            ->where(function (Builder $q) use ($to, $from) {
                $q->where(function (Builder $q) use ($from) {
                    $q->where('since', '=', null);
                    $q->orWhere('since', '<=', $from);
                });
                $q->where(function (Builder $q) use ($to) {
                    $q->where('until', '=', null);
                    $q->orWhere('until', '>=', $to);
                });
            })
            ->exists();
    }
}
