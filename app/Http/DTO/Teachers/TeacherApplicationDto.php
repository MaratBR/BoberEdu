<?php


namespace App\Http\DTO\Teachers;


use App\Http\DTO\DtoBase;
use App\Http\DTO\Users\UserDto;
use App\TeacherApprovalForm;

class TeacherApplicationDto extends DtoBase
{
    protected $r;

    public function __construct(TeacherApprovalForm $form)
    {
        $this->r = $form;
    }

    public function getId()
    {
        return $this->r->id;
    }

    public function getApprovedBy()
    {
        return $this->r->admin_id === null ? null : new UserDto($this->r->admin);
    }

    public function getUser()
    {
        return new UserDto($this->r->user);
    }

    public function isApproved()
    {
        return $this->r->approved;
    }
}
