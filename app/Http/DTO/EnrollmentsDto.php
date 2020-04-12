<?php


namespace App\Http\DTO;


use App\Enrollment;
use Illuminate\Database\Eloquent\Collection;

class EnrollmentsDto extends DtoBase
{
    private $enrolls;

    public function __construct(Collection $enrolls)
    {
        $this->enrolls = $enrolls;
    }

    public function getEnrolls()
    {
        return collect($this->enrolls)->mapInto(EnrollmentDto::class);
    }
}
