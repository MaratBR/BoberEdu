<?php


namespace App\Providers\Services;


use App\Course;
use App\Exceptions\ThrowUtils;
use App\Providers\Services\Abs\IAttendanceStatus;
use App\Providers\Services\Abs\IJoinCourseInfo;
use App\Providers\Services\Abs\IJoinCourseService;
use App\Providers\Services\Abs\IPurchasesService;
use App\Providers\Services\Abs\IRedirectService;
use App\Purchase;
use App\User;
use App\UserCourse;
use App\UserCoursePurchase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Lanin\Laravel\ApiExceptions\BadRequestApiException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class JoinCourseService implements IJoinCourseService
{
    use ThrowUtils;
    private $purchases;
    private $redirects;

    public function __construct(IPurchasesService $service, IRedirectService $redirectService)
    {
        $this->redirects = $redirectService;
        $this->purchases = $service;
    }


    /**
     * @param Course $course
     * @param User $user
     * @return Builder
     */
    private function builder(Course $course, User $user)
    {
        return UserCourse::query()
            ->where('course_id', $course->id)
            ->where('user_id', $user->id);
    }

    /**
     * @inheritDoc
     */
    function get(Course $course, User $user): UserCourse
    {
        return $this->builder($course, $user)->firstOrFail();
    }

    /**
     * @inheritDoc
     */
    function join(Course $course, User $user): UserCourse
    {
        $this->throwErrorIf(409, "You already joined this course", $this->builder($course, $user)->exists());

        $assoc = new UserCourse([
            'user_id' => $user->id,
            'course_id' => $course->id
        ]);

        $assoc->save();
        $assoc->refresh();
        return $assoc;
    }

    private function findPurchase(UserCourse $record): ?Purchase {
        $userCoursePurchase = UserCoursePurchase::query()
            ->where('user_course_id', '=', $record->id)
            ->whereHas('purchase', function (Builder $q) {
                $q->where('status', '=', 'pending');
            })
            ->first();

        if ($userCoursePurchase == null)
            return null;

        return $userCoursePurchase->purchase;
    }

    /**
     * @inheritDoc
     */
    function purchase(UserCourse $record, User $user): Purchase
    {
        /** @type $purchase Purchase */
        $purchase = $this->findPurchase($record);

        if ($purchase != null)
            return $purchase;

        $purchase = $this->purchases->create(
            "Course {$record->course->name} @ BoberEdu",
            $this->redirects->paymentRedirect($record),
            $record->course->price,
            $user
        );

        $userCoursePurchase = new UserCoursePurchase([
            'purchase_id' => $purchase->id,
            'user_course_id' => $record->id
        ]);
        $userCoursePurchase->save();

        return $purchase;
    }
}
