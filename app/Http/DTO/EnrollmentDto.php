<?php


namespace App\Http\DTO;


use App\Enrollment;

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

    public function isActivated()
    {
        return $this->enrollment->activated;
    }
}
