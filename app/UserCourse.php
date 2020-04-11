<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCourse extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'course_id', 'activated'
    ];
}
