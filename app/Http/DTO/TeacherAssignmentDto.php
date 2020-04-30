<?php


namespace App\Http\DTO;


use App\TeachingAssignment;
use Carbon\Carbon;

class TeacherAssignmentDto extends DtoBase
{
    private $assignment;

    public function __construct(TeachingAssignment $assignment)
    {
        $this->assignment = $assignment;
    }

    public function getSince(): Carbon
    {
        return $this->assignment->since;
    }

    public function getUntil(): Carbon
    {
        return $this->assignment->until;
    }
}
