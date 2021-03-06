<?php


namespace App\Services\Implementation;


use App\Models\Enrollment;
use App\Exceptions\ThrowUtils;
use App\Services\Abs\ICourseService;
use App\Services\Abs\IEnrollmentService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Lanin\Laravel\ApiExceptions\NotFoundApiException;

class EnrollmentService implements IEnrollmentService
{
    use ThrowUtils;

    private $courses;

    public function __construct(ICourseService $courses)
    {
        $this->courses = $courses;
    }

    public function getEnrollmentRecordWithPaymentOrNull(int $courseId, User $user): ?Enrollment
    {
        /** @var Enrollment|null $record */
        $record = $this->builder($courseId, $user)->with('payment')->first();
        return $record;
    }

    private function builder(int $courseId, User $user)
    {
        return Enrollment::query()
            ->where('user_id', '=', $user->id)
            ->where('course_id', '=', $courseId);
    }

    function hasEnrollment(int $courseId, User $user): bool
    {
        return $this->builder($courseId, $user)->exists();
    }

    function hasActivatedEnrollment(int $courseId, User $user): bool
    {
        return $this->builder($courseId, $user)
            ->where('activated', '=', true)
            ->exists();
    }

    function hasAccess(int $courseId, User $user): bool
    {
        return $this->builder($courseId, $user)
            ->where(function (Builder $q) {
                $q->where('activated', '=', true)
                    ->orWhere(function (Builder $q) {
                        $q
                            ->where("trial_ends_at", "!=", null)
                            ->where("trial_ends_at", ">=", Carbon::now('UTC'));
                    });
            })
            ->exists();
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

    function getUserEnrolls(User $user)
    {
        return Enrollment::query()
            ->where('user_id', '=', $user->id)
            ->get();
    }

    function getUserEnrollsWithCourses(User $user)
    {
        return Enrollment::query()
            ->where('user_id', '=', $user->id)
            ->with(['course'])
            ->orderBy('activated')
            ->orderBy('enrolled_at')
            ->take(4)
            ->get();
    }

    function activate(Enrollment $record): void
    {
        $this->setActivated($record, true);
    }

    private function setActivated(Enrollment $enrollment, bool $value)
    {
        $enrollment->update([
            'activated' => $value
        ]);
    }

    function deactivate(Enrollment $record): void
    {
        $this->setActivated($record, false);
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
