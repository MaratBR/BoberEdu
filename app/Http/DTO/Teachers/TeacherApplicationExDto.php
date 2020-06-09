<?php


namespace App\Http\DTO\Teachers;


class TeacherApplicationExDto extends TeacherApplicationDto
{
    public function getEducation()
    {
        return $this->r->education;
    }

    public function getLocation()
    {
        return $this->r->location;
    }

    public function getExtra()
    {
        return $this->r->extra;
    }


    public function getDegree()
    {
        return $this->r->degree;
    }
}
