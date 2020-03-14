<?php

namespace App\Policies;

use App\Lesson;
use App\Providers\Services\Abs\ICourseAttendanceService;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LessonPolicy
{
    use HandlesAuthorization;

    private $courseAttendanceService;

    public function __construct(ICourseAttendanceService $courseAttendanceService)
    {
        $this->courseAttendanceService = $courseAttendanceService;
    }

    /**
     *
     * @param User|null $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    public function view(User $user, Lesson $lesson)
    {
        return $this->courseAttendanceService->attendanceStatus(
            $lesson->unit->course_id, $user
        )->hasAccess();
    }

    public function create(User $user)
    {
        // TODO This is debatable, does it really need to be an admin and admin only, can teacher add new lessons to a course?
        return $user->isAdmin();
    }

    public function update(User $user, Lesson $lesson)
    {
        return $user->isAdmin(); // TODO or if it's a teacher
    }

    public function delete(User $user, Lesson $lesson)
    {
        return $user->isAdmin();
    }
}
