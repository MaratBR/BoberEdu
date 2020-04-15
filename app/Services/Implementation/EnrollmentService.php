<?php


namespace App\Services\Implementation;


use App\Course;
use App\Enrollment;
use App\Exceptions\ThrowUtils;
use App\Services\Abs\ICourseService;
use App\Services\Abs\IEnrollmentService;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Lanin\Laravel\ApiExceptions\NotFoundApiException;

class EnrollmentService implements IEnrollmentService
{
    use ThrowUtils;
    private $courses;

    public function __construct(ICourseService $courses)
    {
        $this->courses = $courses;
    }

    private function builder(int $courseId, User $user)
    {
        return Enrollment::query()
            ->where('user_id', '=', $user->id)
            ->where('course_id', '=', $courseId);
    }

    function getEnrollmentRecord(int $courseId, User $user): Enrollment
    {
        /** @var Enrollment $record */
        $record = $this->getEnrollmentRecordOrNull($courseId, $user);

        $this->throwNotFoundIfNull($record, "You are not enrolled in this course");

        return $record;
    }

    function getEnrollmentRecordOrNull(int $courseId, User $user): ?Enrollment
    {
        /** @var Enrollment|null $record */
        $record = $this->builder($courseId, $user)->first();
        return $record;
    }

    public function getEnrollmentRecordWithPaymentOrNull(int $courseId, User $user): ?Enrollment
    {
        /** @var Enrollment|null $record */
        $record = $this->builder($courseId, $user)->with('payment')->first();
        return $record;
    }

    function hasEnrollment(int $courseId, User $user): bool
    {
        return $this->builder($courseId, $user)->exists();
    }

    function isEnrollmentTrashed(int $courseId, User $user): bool
    {
        return Enrollment::withTrashed()
            ->where('user_id', '=', $user->id)
            ->where('course_id', '=', $courseId)
            ->whereNotNull('deleted_at')
            ->exists();
    }

    function createEnrollRecord(int $courseId, User $user): Enrollment
    {
        $trialDays = $this->courses->getTrialDays($courseId);
        Enrollment::create([
            'course_id' => $courseId,
            'user_id' => $user->id,
            'trial_ends_at' => Carbon::now('UTC')->addDays($trialDays)
        ]);

        // Alternative to refresh
        return $this->getEnrollmentRecord($courseId, $user);
    }


    function getUserEnrolls(User $user)
    {
        return Enrollment::query()
            ->where('user_id', '=', $user->id)
            ->get();
    }

    function activate(Enrollment $record): void
    {
        $this->setActivated($record, true);
    }

    function deactivate(Enrollment $record): void
    {
        $this->setActivated($record, false);
    }

    private function setActivated(Enrollment $enrollment, bool $value)
    {
        $enrollment->update([
            'activated' => $value
        ]);
    }

    function delete(int $courseId, User $user)
    {
        $this->builder($courseId, $user)->delete();
    }

    function restore(int $courseId, User $user)
    {
        Enrollment::withTrashed()
            ->where('user_id', '=', $user->id)
            ->where('course_id', '=', $courseId)
            ->restore();
    }
}
