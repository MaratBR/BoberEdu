<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @property int course_id
 * @property int user_id
 * @property boolean activated
 */
class Enrollment extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'course_id', 'activated'
    ];

    protected $casts = [
        'activated' => 'bool'
    ];
}
