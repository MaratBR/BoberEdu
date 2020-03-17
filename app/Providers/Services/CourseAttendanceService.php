<?php


namespace App\Providers\Services;


use App\Course;
use App\CourseAttendance;
use App\Exceptions\ThrowUtils;
use App\Providers\Services\Abs\IAttendanceStatus;
use App\Providers\Services\Abs\ICourseAttendanceInfo;
use App\Providers\Services\Abs\ICourseAttendanceService;
use App\Providers\Services\Abs\IPurchasesService;
use App\Purchase;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Lanin\Laravel\ApiExceptions\BadRequestApiException;
use Lanin\Laravel\ApiExceptions\ConflictApiException;

class CourseAttendanceService implements ICourseAttendanceService
{
    use ThrowUtils;
    private $purchases;

    public function __construct(IPurchasesService $service)
    {
        $this->purchases = $service;
    }

    private function getOrNull(int $courseId, User $user): ?CourseAttendance
    {
        return CourseAttendance::query()
            ->where('course_id', '=', $courseId)
            ->where('user_id', '=', $user->id)
            ->orWhere('gifted_by_id', '=', $user->id)
            ->with(['purchase'])
            ->first();
    }

    public function get(int $courseId, User $user): CourseAttendance
    {
        return $this->throwNotFoundIfNull(
            $this->getOrNull($courseId, $user),
            "We haven't found a record of you attending course with id = $courseId"
        );
    }

    public function exists(int $courseId, User $user): bool
    {
        return CourseAttendance::query()
            ->where('course_id', '=', $courseId)
            ->where('user_id', '=', $user->id)
            ->exists();
    }

    function attend(Course $course, User $user, ICourseAttendanceInfo $info): CourseAttendance
    {
        if (!$course->canBePurchased())
            throw new BadRequestApiException("This course cannot be purchased, hence you cannot attend it");

        $giftTo = $info->giftTo();

        if ($giftTo !== null)
        {
            if ($info->isPreview())
                throw new BadRequestApiException("You cannot gift a preview course");
            $giftTo = User::findOrFail($giftTo);
        }

        $userId = $giftTo ? $giftTo->id : $user->id;
        $giftBy = $giftTo ? $user->id : null;

        $this->throwErrorIf(
            409,
            $giftTo ? "This user already attend this course" : "You already attend this course, check list of your courses",
            $this->exists($course->id, $user)
        );


        $attendanceStatus = $info->isPreview() ? 'preview' : 'inactive';
        $attendance = new CourseAttendance([
            'user_id' => $userId,
            'gifted_by_id' => $giftBy,
            'status' => $attendanceStatus
        ]);
        $attendance->course_id = $course->id;

        $attendance->save();
        $attendance->refresh();

        return $attendance;
    }

    public function getAttendanceStatusFrom(?CourseAttendance $attendance): IAttendanceStatus
    {
        return new Abs\AttendanceStatus($attendance, 7);
    }

    public function attendanceStatus(int $courseId, User $user)
    {
        return $this->getAttendanceStatusFrom(
            $this->getOrNull($courseId, $user)
        );
    }

    public function attachNewPurchase(CourseAttendance $attendance, User $user)
    {
        $attendance->purchase_id = $this->purchases->create(
            "Course {$attendance->course->name} #{$attendance->course->id} @ Bober.edu",
            "", // TODO
            $attendance->course->price,
            $user
        )->id;
    }

    public function makePurchase(CourseAttendance $attendance, User $customer): Purchase
    {
        $status = $this->getAttendanceStatusFrom($attendance);


        if (!$status->isAwaitingPayment())
        {
            $this->attachNewPurchase($attendance, $customer);
        }

        return $attendance->purchase;
    }
}
