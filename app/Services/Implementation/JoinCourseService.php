<?php


namespace App\Service\Implementation;


use App\Course;
use App\Exceptions\ThrowUtils;
use App\Purchase;
use App\Services\Abs\IJoinCourseService;
use App\Services\Abs\IPurchasesService;
use App\Services\Abs\IRedirectService;
use App\User;
use App\UserCourse;
use App\UserCoursePurchase;
use Illuminate\Database\Eloquent\Builder;

class JoinCourseService implements IJoinCourseService
{
    private $purchases;
    private $redirects;

    public function __construct(IPurchasesService $service, IRedirectService $redirectService)
    {
        $this->redirects = $redirectService;
        $this->purchases = $service;
    }

    /**
     * @inheritDoc
     */
    function get(Course $course, User $user): UserCourse
    {
        return $this->builder($course, $user)->firstOrFail();
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
    function join(Course $course, User $user): UserCourse
    {
        $assoc = new UserCourse([
            'user_id' => $user->id,
            'course_id' => $course->id
        ]);

        $assoc->save();
        $assoc->refresh();
        return $assoc;
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

    private function findPurchase(UserCourse $record): ?Purchase
    {
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
}
