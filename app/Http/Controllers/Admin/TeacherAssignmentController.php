<?php

namespace App\Http\Controllers\Admin;

use App\AuditRecord;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teachers\AssignmentRequest;
use App\Services\Abs\ICourseService;
use App\Services\Abs\ITeachersService;
use App\Utils\Audit\Audit;

class TeacherAssignmentController extends Controller
{
    private $repo;
    private $courses;

    public function __construct(ITeachersService $teachersService, ICourseService $courses)
    {
        $this->repo = $teachersService;
        $this->courses = $courses;
    }

    public function assign(AssignmentRequest $request, int $teacherId, int $courseId)
    {
        if ($this->repo->hasAssignment($teacherId, $courseId)) {
            return $this->noChanges();
        }

        $teacher = $this->repo->get($teacherId);
        $course = $this->courses->get($courseId);
        $this->repo->assign($teacher, $course);

        AuditRecord::make($request->user(), $request, Audit::ASSIGN_TEACHER)
            ->subject($teacher)->data(['c' => $courseId])->comment($request->getReason())->build();

        return $this->done();
    }

    public function revoke(AssignmentRequest $request, int $teacherId, int $courseId)
    {
        if (!$this->repo->hasAssignment($teacherId, $courseId)) {
            return $this->noChanges();
        }

        $teacher = $this->repo->get($teacherId);
        $course = $this->courses->get($courseId);
        $this->repo->revoke($teacher, $course);

        AuditRecord::make($request->user(), $request, Audit::REVOKE_TEACHER)
            ->subject($teacher)->data(['c' => $courseId])->comment($request->getReason())->build();

        return $this->done();
    }
}
