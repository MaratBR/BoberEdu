<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static Enrollment create(array $array)
 * @property int course_id
 * @property int user_id
 * @property boolean activated
 * @property Carbon trial_ends_at
 */
class Enrollment extends CompositeModel
{
    protected $primaryKey = ['user_id', 'course_id'];
    public $timestamps = false;
    public $incrementing = false;

    use SoftDeletes;

    protected $fillable = [
        'user_id', 'course_id', 'activated', 'trial_ends_at'
    ];

    protected $casts = [
        'activated' => 'bool'
    ];

    protected $dates = [
        'enrolled_at', 'trial_ends_at'
    ];
}
