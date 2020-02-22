<?php

namespace App\Http\Controllers;

use App\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    function get(Request $request) {
        $id = $request->id;

        $course = Course::findOrFail($id);
        return $course;
    }

    function create(Request $request) {
        $data = $request->validate(Course::$rules);
        $course = Course::create($data);
        return $course;
    }

    function update(Request $request) {
        $data = $request->validate(Course::$updateRules);
        $course = Course::getById($request->id);
        $course->update($data);
        $course->save();
        return response()->noContent();
    }

    function delete(Request $request) {
        Course::findOrFail($request->id)->delete();
        return response()->noContent();
    }
}
