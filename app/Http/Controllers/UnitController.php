<?php

namespace App\Http\Controllers;

use App\Course;
use App\Unit;
use Illuminate\Http\Request;

class UnitController extends CRUDController
{
    public function __construct()
    {
        $this->configure(
            Unit::class,
            Unit::$rules,
            Unit::$updateRules,
            'unit'
        );
    }
}
