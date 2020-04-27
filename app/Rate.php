<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Rate extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'value', 'course_id'
    ];
}
