<?php

namespace App\Http\Controllers\Admin;

use App\AuditRecord;
use App\Http\Controllers\Controller;
use App\Http\DTO\PaginationDto;
use App\Http\DTO\Teachers\AdminTeacherDto;
use App\Http\DTO\Teachers\TeacherAssignmentDto;
use App\Http\DTO\Teachers\TeacherDto;
use App\Http\DTO\Uploads\UploadedDto;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\Teachers\AssignTeacherRequest;
use App\Http\Requests\Teachers\CreateTeacherRequest;
use App\Http\Requests\Teachers\UpdateTeacherRequest;
use App\Services\Abs\ICourseService;
use App\Services\Abs\ITeachersService;
use App\Services\Abs\IUploadService;
use App\Services\Abs\IUsersService;
use App\User;
use App\Utils\Audit\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeachersController extends Controller
{
    private $repo;
    private $courses;

    public function __construct(ITeachersService $teachers, ICourseService $courses)
    {
        $this->repo = $teachers;
        $this->courses = $courses;
    }


    public function get(int $teacherId)
    {
        $teacher = $this->repo->get($teacherId);

        return new AdminTeacherDto($teacher);
    }

    public function update(UpdateTeacherRequest $request, int $teacherId)
    {
        $teacher = $this->repo->get($teacherId);
        $payload = $request->getPayload();
        $teacher->update($payload);

        AuditRecord::make($request->user(), $request, Audit::UPDATE)
            ->subject($teacher)->build();

        return new AdminTeacherDto($teacher);
    }

    public function assign(AuthenticatedRequest $request, int $teacherId, int $courseId)
    {
        $teacher = $this->repo->get($teacherId);
        $course = $this->courses->get($courseId);


        if ($this->repo->hasAssignment($teacher, $course))
        {
            $this->throwError(409, "This teacher already has assignment for this course");
        }

        $this->repo->assign($teacher, $course);

        AuditRecord::make($request->user(), $request, Audit::ASSIGN_TEACHER)
            ->subject($teacher->id)->data(['c' => $course->id])->build();
    }

    public function revoke(AuthenticatedRequest $request, int $teacherId, int $courseId)
    {
        $teacher = $this->repo->get($teacherId);
        $course = $this->courses->get($courseId);
        $this->repo->revoke($teacher, $course);

        AuditRecord::make($request->user(), $request, Audit::REVOKE_TEACHER)
            ->subject($teacher->id)->data([
                'c' => $course->id
            ])
            ->build();
    }

    public function paginate() {
        $paginator = $this->repo->paginate();

        return new PaginationDto($paginator, TeacherDto::class);
    }

    public function create(CreateTeacherRequest $request, IUsersService $users)
    {
        $user = $users->get($request->getUserId());
        $this->throwErrorIf(
            409,
            "Teacher already exists",
            $this->repo->exists($user)
        );
        $teacher = $this->repo->create($user, $request->getPayload());


        AuditRecord::make($request->user(), $request, Audit::CREATE)
            ->subject($teacher)
            ->data(['u' => $user->id])
            ->comment($request->getComment())
            ->build();

        return $this->created(new TeacherDto($teacher));
    }

    public function delete(AuthenticatedRequest $request, int $teacherId)
    {
        $teacher = $this->repo->get($teacherId);

        AuditRecord::make($request->user(), $request, Audit::DELETE)
            ->subject($teacher->id)
            ->build();

        return $this->deleteShortcut($teacher->delete());
    }

    public function uploadAvatar(AuthenticatedRequest $request, int $id, IUploadService $uploadService) {
        $teacher = $this->repo->get($id);

        $avatar = $uploadService->uploadAvatar($request->user(), $this->openInput());

        $teacher->update([
            'avatar_id' => $avatar->id
        ]);

        return new UploadedDto($avatar);
    }

    public function search(SearchRequest $request)
    {
        if ($request->getQuery() === null) {
            return $this->paginate();
        }

        return new PaginationDto($this->repo->search($request->getQuery())->paginate(), TeacherDto::class);
    }

    public function approveForm(AuthenticatedRequest $request, int $formId)
    {
        $this->setFormApproved($request, $formId, true, $request->input('comment'));
    }

    public function disapproveForm(AuthenticatedRequest $request, int $formId)
    {
        $this->setFormApproved($request, $formId, true, $request->input('comment'));
    }

    private function setFormApproved(AuthenticatedRequest $request, int $formId, bool $approval, ?string $comment = null)
    {
        $form = $this->repo->getApprovalForm($formId);
        $form->update([
            'approved' => $approval,
            'user_id' => $request->user()->id
        ]);

        AuditRecord::make($request->user(), $request, $approval ? Audit::APPROVE_TEACHER : Audit::DISAPPROVE_TEACHER)
            ->subject($form)->comment($comment)->build();
    }
}
