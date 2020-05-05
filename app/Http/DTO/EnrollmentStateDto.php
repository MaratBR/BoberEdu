<?php


namespace App\Http\DTO;


use App\Enrollment;

class EnrollmentStateDto extends DtoBase
{
    public $hasAccess;
    public $enrolled;
    public $paymentSuccessful;
    public $trialEnd;

    public function __construct(bool $hasAccess, bool $enrolled, bool $paymentSuccessful, ?Enrollment $enrollment)
    {
        $this->paymentSuccessful = $paymentSuccessful;
        $this->enrolled = $enrolled;
        $this->hasAccess = $hasAccess;
        $this->trialEnd = ($enrollment && !$enrollment->activated) ? $enrollment->trial_ends_at : null;
    }
}
