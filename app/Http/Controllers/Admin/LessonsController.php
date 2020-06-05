<?php

namespace App\Http\Controllers\Admin;

use App\AuditRecord;
use App\Http\Controllers\Controller;
use App\Http\DTO\Lessons\LessonExDto;
use App\Http\Requests\Lessons\CreateNewLessonRequest;
use App\Http\Requests\Lessons\DeleteLessonRequest;
use App\Http\Requests\Lessons\UpdateLessonRequest;
use App\Services\Abs\ILessonsService;
use App\Utils\Audit\Audit;
use Illuminate\Http\Request;

class LessonsController extends Controller
{
    private $repo;

    public function __construct(ILessonsService $lessons)
    {
        $this->repo = $lessons;
    }

    public function get(int $lessonId)
    {
        return new LessonExDto($this->repo->getOverview($lessonId));
    }

    public function create(CreateNewLessonRequest $request)
    {
        $lessons = $this->repo->create($request->getPayload());
        return new LessonExDto($lessons);
    }

    public function update(int $lessonId, UpdateLessonRequest $request)
    {
        $lesson = $this->repo->getOverview($lessonId);
        $lesson->update($request->getPayload());

        AuditRecord::builder()
            ->request($request)
            ->action(Audit::UPDATE)
            ->subject($lesson)
            ->build();

        return new LessonExDto($lesson);
    }

    public function delete(int $lessonId, Request $request)
    {
        $lesson = $this->repo->getOverview($lessonId);

        AuditRecord::builder()
            ->request($request)
            ->action(Audit::DELETE)
            ->subject($lesson)
            ->build();

        return $this->deleteShortcut($lesson->delete());
    }
}
