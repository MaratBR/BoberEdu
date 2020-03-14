<?php


namespace App\Providers\Services;


use App\Course;
use App\CourseAttendance;
use App\Providers\Services\Abs\IAttendanceStatus;
use App\Providers\Services\Abs\ICourseAttendanceInfo;
use App\Providers\Services\Abs\ICourseAttendanceService;
use App\Providers\Services\Abs\IPurchasesService;
use App\User;
use Lanin\Laravel\ApiExceptions\BadRequestApiException;
use Lanin\Laravel\ApiExceptions\ConflictApiException;

class CourseAttendanceService implements ICourseAttendanceService
{
    private $purchases;

    public function __construct(IPurchasesService $service)
    {
        $this->purchases = $service;
    }

    function attend(Course $course, User $user, ICourseAttendanceInfo $info): CourseAttendance
    {
        $giftTo = $info->giftTo();

        if ($giftTo !== null)
        {
            if ($info->isPreview())
                throw new BadRequestApiException("You cannot gift a preview course");
            $giftTo = User::findOrFail($giftTo);
        }

        $userId = $giftTo ? $giftTo->id : $user->id;
        $giftBy = $giftTo ? $user->id : null;

        if (CourseAttendance::hasRecord($userId, $course->id))
        {
            throw new ConflictApiException(
                $giftTo ? "This user already attend this course" : "You already attend this course");
        }


        $attendance = new CourseAttendance([
            'user_id' => $userId,
            'gifted_by_id' => $giftBy,
            'preview' => $info->isPreview()
        ]);
        $attendance->course = $course;

        if (!$info->isPreview())
        {
            $this->attachNewPurchase($attendance, $user);
        }

        $attendance->save();
        $attendance->refresh();

        return $attendance;
    }

    function attendanceStatus(int $courseId, User $user): IAttendanceStatus
    {
        $attendance = CourseAttendance::query()
            ->where('user_id', '=', $user->id)
            ->where('course_id', '=', $courseId)
            ->first();

        return new Abs\AttendanceStatus($attendance, 7);
    }


    function attachNewPurchase(CourseAttendance $attendance, User $user)
    {
        $attendance = $this->purchases->create(
            "Course {$attendance->course->name} #{$attendance->course->id} @ Bober.edu",
            "", // TODO
            $attendance->course->price,
            $user
        );
    }
}
