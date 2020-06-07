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
}
