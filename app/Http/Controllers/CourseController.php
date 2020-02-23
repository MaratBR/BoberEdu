<?php

namespace App\Http\Controllers;

use App\Course;
use Illuminate\Http\Request;

class CourseController extends CRUDController
{
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
