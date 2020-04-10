<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticatedRequest;
use App\Services\Abs\ICourseService;
use App\Services\Abs\IExternalPaymentService;
use App\Services\Abs\IJoinCourseService;
use App\Services\Abs\IPurchasesService;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentsController extends Controller
{
    private $purchases;
    private $external;
    private $attendances;
    private $courses;


    public function __construct(
        IJoinCourseService $attendances,
        IPurchasesService $purchases,
        IExternalPaymentService $paymentService,
        ICourseService $courses
    )
    {
        $this->purchases = $purchases;
        $this->attendances = $attendances;
        $this->external = $paymentService;
        $this->courses = $courses;
    }

    public function check(AuthenticatedRequest $request, int $courseId)
    {
        $course = $this->courses->get($courseId);
        $attendance = $this->attendances->get($course, $request->user());

        $status = $this->external->checkPaymentStatus($purchase->external_id);

        if ($status->status() !== $purchase->status) {
            $purchase->update([
                'status' => $status->status(),
                'completed_at' => Carbon::now('UTC')
            ]);
            $purchase->refresh();
        }

        return $purchase;
    }
}
