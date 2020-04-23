<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property Carbon since
 * @property Carbon until
 * @property int id
 * @method static findOrFail(int $assignmentId)
 */
class TeachingPeriod extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'since', 'until', 'teacher_id', 'course_id'
    ];

    protected $dates = [
        'since', 'until'
    ];
}
