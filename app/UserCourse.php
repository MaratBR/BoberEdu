<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCourse extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'purchase_id', 'active'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function purchase() {
        return $this->belongsTo(Purchase::class);
    }
}
