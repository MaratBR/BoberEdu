<?php

namespace App\Http\Controllers;

use App\Http\DTO\EnrollmentDto;
use App\Http\DTO\EnrollmentsDto;
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

        $record = $this->enrollment->getEnrollmentRecordOrNull($courseId, $user);

        if ($record == null)
        {
            $record = $this->enrollment->enroll($courseId, $user);
        }
        return $this->noContent();
    }

    public function disenroll(AuthenticatedRequest $request, int $courseId)
    {
        if ($this->enrollment->hasEnrollment($courseId, $request->user()))
        {
            $this->enrollment->disenroll($courseId, $request->user());
        }

        return $this->noContent();
    }
}
