<?php

namespace App\Http\Controllers;

use App\Http\DTO\Teachers\TeacherProfileDto;
use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\Teachers\ApprovalFormRequest;
use App\Services\Abs\ICourseService;
use App\Services\Abs\ITeachersService;
use App\Services\Abs\IUsersService;

class TeacherController extends Controller
{
    private $teachers;
    private $users;
    private $courses;


    public function __construct(ITeachersService $teachersService, IUsersService $usersService, ICourseService $courseService)
    {
        $this->courses = $courseService;
        $this->users = $usersService;
        $this->teachers = $teachersService;
    }

    public function get(int $teacherId)
    {
        $teacher = $this->teachers->get($teacherId);
        return new TeacherProfileDto($teacher);
    }

    public function sendApprovalForm(ApprovalFormRequest $request)
    {
        $d = $request->getPayload();
        $this->teachers->createApprovalForm($request->user(), $d);

        return $this->done(201);
    }

    public function approvalStatus(AuthenticatedRequest $request)
    {
        $form = $this->teachers->getLastApprovalForm($request->user());
        return [
            'state' =>
                $form === null ?
                    null :
                    (
                    $form->approved === null ?
                        'awaiting' :
                        (
                        $form->approved ?
                            'approved' :
                            'rejected'
                        )
                    )
        ];
    }
}
