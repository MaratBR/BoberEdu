<?php

namespace App\Http\Controllers;

use App\AuditRecord;
use App\Http\DTO\PaginationDto;
use App\Http\DTO\Teachers\TeacherAssignmentDto;
use App\Http\DTO\Teachers\TeacherDto;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\Teachers\AssignTeacherRequest;
use App\Http\Requests\Teachers\CreateTeacherRequest;
use App\Http\Requests\Teachers\UpdateTeacherRequest;
use App\Services\Abs\ICourseService;
use App\Services\Abs\ITeachersService;
use App\Services\Abs\IUsersService;
use App\Utils\Audit\Audit;

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
        return new TeacherDto($teacher);
    }
}
