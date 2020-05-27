<?php

namespace App\Http\Controllers\Admin;

use App\AuditRecord;
use App\Http\Controllers\Controller;
use App\Http\DTO\Lessons\LessonExDto;
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

    public function update(int $lessonId, UpdateLessonRequest $request)
    {
        $lesson = $this->repo->getOverview($lessonId);
        $lesson->update($request->getPayload());

        AuditRecord::builder()
            ->request($request)
            ->action(Audit::LESSON_UPDATE)
            ->subject($lessonId)
            ->build();

        return new LessonExDto($lesson);
    }

    public function delete(int $lessonId, Request $request)
    {
        $lesson = $this->repo->getOverview($lessonId);

        AuditRecord::builder()
            ->request($request)
            ->action(Audit::LESSON_DELETE)
            ->subject($lessonId)
            ->build();

        return $this->deleteShortcut($lesson->delete());
    }
}
