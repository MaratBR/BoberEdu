<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static Teacher findOrFail(int $id)
 * @method static Teacher create(array $data)
 */
class Teacher extends Model
{
    protected $fillable = [
        'full_name', 'passport_num', 'user_id'
    ];
}
