<?php


namespace App\Http\DTO;


use App\Models\Enrollment;
use Carbon\Carbon;

class EnrollmentDto extends DtoBase
{
    private $enrollment;

    public function __construct(Enrollment $enrollment)
    {
        $this->enrollment = $enrollment;
    }

    public function getCourseId()
    {
        return $this->enrollment->course_id;
    }

    public function getTrialEnd(): ?Carbon
    {
        return $this->enrollment->trial_ends_at;
    }

    public function getFirstJoined(): Carbon
    {
        return $this->enrollment->enrolled_at;
    }
}
