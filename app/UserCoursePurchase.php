<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCoursePurchase extends Model
{
    protected $fillable = [
        'purchase_id', 'user_course_id'
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function userCourse()
    {
        return $this->belongsTo(UserCourse::class);
    }
}
