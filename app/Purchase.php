<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail(int $id)
 * @method static create(array $array)
 */
class Purchase extends Model
{
    const STATUS_SUCCESSFUL = 'successful';
    const STATUS_PENDING = 'pending';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    public $timestamps = false;

    protected $fillable = [
        'price', 'external_id', 'course_attendance_id',
        'status', 'user_id'
    ];

    public function user() {
        return $this->hasOne(User::class);
    }

    public function courseAttendance() {
        return $this->hasOne(CourseAttendance::class);
    }
}
