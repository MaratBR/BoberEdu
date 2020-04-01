<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int course_id
 * @property int user_id
 * @property boolean active
 *
 * @property Purchase purchase
 * @property User user
 * @property Course course
 * @property int id
 */
class UserCourse extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchases()
    {
        return $this->belongsToMany(Purchase::class, 'user_course_purchases');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
