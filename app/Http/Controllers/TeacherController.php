<?php

namespace App\Http\Controllers;

use App\AuditRecord;
use App\Http\DTO\TeacherAssignmentDto;
use App\Http\DTO\TeacherDto;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\Teachers\AssignTeacherRequest;
use App\Http\Requests\Teachers\CreateTeacherRequest;
use App\Http\Requests\Teachers\UpdateTeacherRequest;
use App\Services\Abs\ICourseService;
use App\Services\Abs\ITeachersService;
use App\Services\Abs\IUsersService;
use App\Utils\Audit\Audit;
use App\Utils\Convert;
use Illuminate\Http\Request;

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

    public function create(CreateTeacherRequest $request)
    {
        $user = $this->users->get($request->getUserId());
        $teacher = $this->teachers->create($user, $request->getPayload());


        AuditRecord::make($request->user(), $request, Audit::TEACHER_NEW)
            ->subject($teacher->id)
            ->data(['u' => $user->id])
            ->comment($request->getComment());

        return $this->created(new TeacherDto($teacher));
    }

    public function update(UpdateTeacherRequest $request, int $teacherId)
    {
        $teacher = $this->teachers->get($teacherId);

        $payload = $request->getPayload();
        $teacher->update($payload);

        AuditRecord::make($request->user(), $request, Audit::TEACHER_UPDATED)
            ->subject($teacher->id)
            ->data(['f' => array_keys($payload)]);
    }

    public function assign(AssignTeacherRequest $request, int $teacherId, int $courseId)
    {
        $this->throwErrorIf(422, "You can't put since after until",
            $request->getSince() >= $request->getUntil());

        $teacher = $this->teachers->get($teacherId);
        $course = $this->courses->get($courseId);


        if ($this->teachers->hasAssignmentDuring($teacher, $course, $request->getSince(), $request->getUntil()))
        {
            $this->throwError(409, "This teacher already has overlapping assignment for this course");
        }

        $assignment = $this->teachers->assign($teacher, $course, $request->getSince(), $request->getUntil());

        AuditRecord::make($request->user(), $request, Audit::TEACHER_ASSIGNED)
            ->subject($teacher->id)->data(['c' => $course->id]);

        return new TeacherAssignmentDto($assignment);
    }

    public function revoke(AdminRequest $request, int $teacherId, int $courseId)
    {
        $teacher = $this->teachers->get($teacherId);
        $course = $this->courses->get($courseId);
        $assignment = $this->teachers->getAssignmentFor($teacher, $course);
        $assignment->delete();

        AuditRecord::make($request->user(), $request, Audit::TEACHER_REVOKED)
            ->subject($teacher->id)->data([
                'c' => $course->id,
                'a' => $assignment->id
            ]);
    }
}
