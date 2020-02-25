<?php

namespace App\Http\Controllers;

use App\Course;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends CRUDController
{
    protected function getById($id, Request $request)
    {
        return $this->scope($this->getQueryBuilder(), $request)
            ->with('units')
            ->findOrFail($id);
    }

    protected function getAll(Request $request)
    {
        return $this->scope($this->getQueryBuilder(), $request)
            ->select('courses.*', DB::raw('COUNT(units.id) as units_count'), DB::raw('COUNT(lessons.id) as lessons  _count'))
            ->leftJoin('units', 'units.course_id', '=', 'courses.id')
            ->leftJoin('lessons', 'lessons.unit_id', '=', 'units.id')
            ->groupBy('courses.id')
            ->get();
    }

    public function __construct()
    {
        $this->configure(
            Course::class,
            Course::$rules,
            Course::$updateRules,
            'course'
        );
    }
}
