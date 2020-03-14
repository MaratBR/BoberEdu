<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Course course
 * @property Purchase purchase
 * @property int course_id
 * @property int user_id
 * @property int gifted_by_id
 * @property int id
 * @property bool preview
 * @property Carbon created_at
 */
class CourseAttendance extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'course_id', 'preview', 'user_id', 'gifted_by_id'
    ];

    protected $dates = [
        'created_at'
    ];

    protected $hidden = [
        'course', 'purchase'
    ];

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    public function course()
    {
        return $this->hasOne(Course::class);
    }
}
