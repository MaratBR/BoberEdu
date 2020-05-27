<?php

namespace App\Http\Controllers;

use App\Http\DTO\Lessons\LessonExDto;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\Lessons\CreateNewLessonRequest;
use App\Http\Requests\Lessons\DeleteLessonRequest;
use App\Http\Requests\Lessons\UpdateLessonRequest;
use App\Lesson;
use App\Services\Abs\IEnrollmentService;
use App\Services\Abs\ILessonsService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LessonsController extends Controller
{
    private $lessons;

    public function __construct(ILessonsService $service)
    {
        $this->lessons = $service;
    }

    public function show(AuthenticatedRequest $request, int $courseId, IEnrollmentService $enrollmentService)
    {
        $lesson = $this->lessons->getOverview($courseId);

        if ($enrollmentService->hasAccess($lesson->unit->course_id, $request->user()))
            return new LessonExDto($lesson);

        throw new HttpException(403, "You don't have access to this course");
    }

    public function showAdmin(AdminRequest $request, int $courseId, IEnrollmentService $enrollmentService)
    {
        $lesson = $this->lessons->getOverview($courseId);

        return new LessonExDto($lesson);
    }

    public function store(CreateNewLessonRequest $request)
    {
        $this->throwForbiddenIfNotAllowed('create', Lesson::class);
        return $this->created(
            $this->lessons->create($request->validated())
        );
    }

    public function destroy(DeleteLessonRequest $request)
    {
        $lesson = $this->lessons->get($request->lesson);
        $this->throwForbiddenIfNotAllowed('delete', $lesson);
        return $this->deleteShortcut(
            $this->lessons->delete(
                $lesson,
                $request->isForce()
            )
        );
    }

    public function update(UpdateLessonRequest $request)
    {
        $lesson = $this->lessons->get($request->lesson);
        $this->throwForbiddenIfNotAllowed('update   ', $lesson);
        $this->updateShortcut(
            $lesson->update($request->validated())
        );
    }
}
