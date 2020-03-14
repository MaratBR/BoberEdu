<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\Lessons\CreateNewLessonRequest;
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

    }
}
