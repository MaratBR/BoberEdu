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

    public function get(int $id, User $user): CourseAttendance
    {
        return $this->throwNotFoundIfNull(
            CourseAttendance::query()
                ->where('id', '=', $id)
                ->where('user_id', '=', $user->id)
                ->orWhere('gifted_by_id', '=', $user->id)
                ->first(),
            "We haven't found a record you are looking for"
        );
    }

    function purchase(Course $course, User $user, ICourseAttendanceInfo $info): CourseAttendance
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

        $status = $this->attendanceStatus($course->id, $giftTo ?? $user);
        $this->throwErrorIf(
            409,
            $giftTo ? "This user already attend this course" : "You already attend this course, check list of your courses",
            $status->exists()
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

    function attendanceStatus(int $courseId, User $user): IAttendanceStatus
    {
        $attendance = CourseAttendance::query()
            ->where('user_id', '=', $user->id)
            ->where('course_id', '=', $courseId)
            ->first();

        // IDEA, it's FINE, I told you
        return $this->getAttendanceStatusFrom($attendance);
    }

    public function getAttendanceStatusFrom(?CourseAttendance $attendance): IAttendanceStatus
    {
        return new Abs\AttendanceStatus($attendance, 7);
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

    public function submitPurchase(CourseAttendance $attendance, User $customer): Purchase
    {
        $status = $this->getAttendanceStatusFrom($attendance);


        if (!$status->isAwaitingPayment())
        {
            $this->attachNewPurchase($attendance, $customer);
        }

        return $attendance->purchase;
    }
}
