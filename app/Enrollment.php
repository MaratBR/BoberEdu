<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static Enrollment create(array $array)
 * @property int course_id
 * @property int user_id
 * @property int payment_id
 * @property boolean activated
 * @property Carbon trial_ends_at
 * @property Carbon enrolled_at
 * @property Payment|null payment
 * @property Course course
 */
class Enrollment extends CompositeModel
{
    protected $primaryKey = ['user_id', 'course_id'];
    public $timestamps = false;
    public $incrementing = false;

    use SoftDeletes;

    protected $fillable = [
        'user_id', 'course_id', 'activated', 'trial_ends_at', 'payment_id'
    ];

    protected $casts = [
        'activated' => 'bool'
    ];

    protected $dates = [
        'enrolled_at', 'trial_ends_at'
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
