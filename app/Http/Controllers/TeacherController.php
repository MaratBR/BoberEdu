<?php

namespace App\Http\Controllers;

use App\AuditRecord;
use App\Http\DTO\TeacherDto;
use App\Http\Requests\Teachers\CreateTeacherRequest;
use App\Http\Requests\Teachers\UpdateTeacherRequest;
use App\Services\Abs\ITeachersService;
use App\Services\Abs\IUsersService;
use App\Utils\Convert;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    private $teachers;
    private $users;


    public function __construct(ITeachersService $teachersService, IUsersService $usersService)
    {
        $this->users = $usersService;
        $this->teachers = $teachersService;
    }

    public function get(int $teacherId)
    {
        $teacher = $this->teachers->get($teacherId);
        return new TeacherDto($teacher);
    }

    public function create(CreateTeacherRequest $request)
    {
        $user = $this->users->get($request->getUserId());
        $teacher = $this->teachers->create($user, [
            'pass_num' => $request->getPassNum(),
            'full_name' => $request->getFullName()
        ]);

        AuditRecord::teacherCreated($request->user(), $request, $user, $teacher);

        return new TeacherDto($teacher);
    }

    public function update(UpdateTeacherRequest $request, int $teacherId)
    {
        $teacher = $this->teachers->get($teacherId);

        $payload = $request->getPayload();
        $teacher->update($payload);

        AuditRecord::teacherUpdate($request->user(), $request, $teacher, array_keys($payload));

    }

    public function assign()
    {

    }

    public function revoke()
    {

    }
}
