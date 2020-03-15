<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\Lessons\CreateNewLessonRequest;
use App\Http\Requests\Lessons\DeleteLessonRequest;
use App\Http\Requests\Lessons\UpdateLessonRequest;
use App\Providers\Services\Abs\ILessonsService;
use Illuminate\Http\Request;

class LessonsController extends Controller
{
    private $lessons;

    public function __construct(ILessonsService $service)
    {
        $this->lessons = $service;
    }

    public function show(AuthenticatedRequest $request)
    {
        return $this->lessons->get($request->lesson);
    }

    public function store(CreateNewLessonRequest $request)
    {
        return $this->created(
            $this->lessons->create($request->validated())
        );
    }

    public function destroy(DeleteLessonRequest $request)
    {
        return $this->deleteShortcut(
            $this->lessons->delete(
                $this->lessons->get($request->lesson),
                $request->isForce()
            )
        );
    }

    public function update(UpdateLessonRequest $request)
    {
        $lesson = $this->lessons->get($request->lesson);
        $this->updateShortcut(
            $lesson->update($request->validated())
        );
    }
}
