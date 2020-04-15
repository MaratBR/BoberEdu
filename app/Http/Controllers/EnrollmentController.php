<?php

namespace App\Http\Controllers;

use App\Http\DTO\EnrollmentDto;
use App\Http\DTO\EnrollmentsDto;
use App\Http\DTO\EnrollmentStateDto;
use App\Http\Requests\AuthenticatedRequest;
use App\Services\Abs\ICourseService;
use App\Services\Abs\IEnrollmentService;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    private $enrollment;

    public function __construct(IEnrollmentService $enrollment)
    {
        $this->enrollment = $enrollment;
    }

    public function listEnrollments(AuthenticatedRequest $request)
    {
        $enrolls = $this->enrollment->getUserEnrolls($request->user());

        return new EnrollmentsDto($enrolls);
    }

    public function enroll(AuthenticatedRequest $request, int $courseId)
    {
        $user = $request->user();

        $exists = $this->enrollment->hasEnrollment($courseId, $user);


        if ($exists) {
            $record = $this->enrollment->getEnrollmentRecord($courseId, $user);
        }
        else {
            if ($this->enrollment->isEnrollmentTrashed($courseId, $user))
            {
                $this->enrollment->restore($courseId, $user);
                $record = $this->enrollment->getEnrollmentRecord($courseId, $user);
            }
            else
            {
                $record = $this->enrollment->createEnrollRecord($courseId, $user);
            }
        }

        return new EnrollmentDto($record);
    }

    public function disenroll(AuthenticatedRequest $request, int $courseId)
    {
        $user = $request->user();
        if ($this->enrollment->hasEnrollment($courseId, $user))
        {
            $this->enrollment->delete($courseId, $user);
        }

        return $this->noContent();
    }

    public function status(AuthenticatedRequest $request, int $courseId)
    {
        $user = $request->user();
        $enrollment = $this->enrollment->getEnrollmentRecordWithPaymentOrNull($courseId, $user);

        $enrolled = !!$enrollment;
        $hasAccess = $enrolled && $enrollment->activated;
        $paymentSuccessful = $enrolled && $enrollment->payment && $enrollment->payment->is_successful;

        return new EnrollmentStateDto(
            $hasAccess,
            $enrolled,
            $paymentSuccessful
        );
    }
}
