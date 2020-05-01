<?php

namespace App\Http\Controllers;

use App\Http\DTO\LessonExDto;
use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\Lessons\CreateNewLessonRequest;
use App\Http\Requests\Lessons\DeleteLessonRequest;
use App\Http\Requests\Lessons\UpdateLessonRequest;
use App\Lesson;
use App\Services\Abs\ILessonsService;

class LessonsController extends Controller
{
    private $lessons;

    public function __construct(ILessonsService $service)
    {
        $this->lessons = $service;
    }

    public function show(AuthenticatedRequest $request)
    {
        $lesson = $this->lessons->get($request->lesson);
        $this->throwForbiddenIfNotAllowed('view', $lesson);
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
