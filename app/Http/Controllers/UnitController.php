<?php

namespace App\Http\Controllers;

use App\Course;
use App\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    function get(Request $request) {
        $course = Course::getById($request->courseId);
        $unit = Unit::get();
    }
}
