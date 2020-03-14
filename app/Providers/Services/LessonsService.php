<?php


namespace App\Providers\Services;


use App\Lesson;
use App\Providers\Services\Abs\ILessonsService;
use App\User;

class LessonsService implements ILessonsService
{

    function get(int $id, User $user): Lesson
    {
        return Lesson::query()
            ->join('units', 'units.id', '=', 'lessons.unit_id')
            ->join('courses', 'courses.id', '=', 'units.course_id')
            ->join('course_attendances', 'course_attendances.course_id', '=', 'courses.id')
            ->where('course_attendances.user_id', '=', $user->id)
            ->where('course_attendances.status', '=', 'active')
            ->findOrFail($id);
    }
}
