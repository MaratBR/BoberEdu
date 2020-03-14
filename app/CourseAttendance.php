<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseAttendance extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'course_id', 'preview', 'user_id', 'gifted_by_id'
    ];

    protected $dates = [
        'created_at'
    ];

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }
}
