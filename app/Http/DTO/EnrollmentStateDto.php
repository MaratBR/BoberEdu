<?php


namespace App\Http\DTO;


class EnrollmentStateDto extends DtoBase
{
    public $hasAccess;
    public $enrolled;
    public $paymentSuccessful;

    public function __construct(bool $hasAccess, bool $enrolled, bool $paymentSuccessful)
    {
        $this->paymentSuccessful = $paymentSuccessful;
        $this->enrolled = $enrolled;
        $this->hasAccess = $hasAccess;
    }
}
