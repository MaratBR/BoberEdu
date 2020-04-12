<?php


namespace App\Services\Implementation;


use App\Course;
use App\Enrollment;
use App\Services\Abs\IEnrollmentService;
use App\User;

class EnrollmentService implements IEnrollmentService
{
    private function builder(int $courseId, User $user)
    {
        return Enrollment::query()
            ->where('user_id', '=', $user->id)
            ->where('course_id', '=', $courseId);
    }

    function getEnrollmentRecord(int $courseId, User $user): Enrollment
    {
        /** @var Enrollment $record */
        $record = $this->builder($courseId, $user)->firstOrFail();

        return $record;
    }

    function getEnrollmentRecordOrNull(int $courseId, User $user): ?Enrollment
    {
        /** @var Enrollment|null $record */
        $record = $this->builder($courseId, $user)->first();
        return $record;
    }

    function hasEnrollment(int $courseId, User $user): bool
    {
        return $this->builder($courseId, $user)->exists();
    }

    function enroll(int $courseId, User $user): Enrollment
    {
        return Enrollment::create([
            'course_id' => $courseId,
            'user_id' => $user->id
        ]);
    }

    function enrollCourse(Course $course, User $user): Enrollment
    {
        return $this->enroll($course->id, $user);
    }

    function disenrollCourse(Course $course, User $user): bool
    {
        return $this->disenroll($course->id, $user);
    }

    function disenroll(int $courseId, User $user): bool
    {
        return $this->builder($courseId, $user)->delete() == true;
    }

    function getUserEnrolls(User $user)
    {
        return Enrollment::query()->where('user_id', '=', $user->id)->get();
    }
}
