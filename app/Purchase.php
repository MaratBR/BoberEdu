<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail(int $id)
 * @method static create(array $array)
 * @property int id
 * @property string external_id
 * @property float price
 * @property string external_redirect_url
 * @property string status
 * @property int user_id
 */
class Purchase extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'price', 'external_id',
        'status', 'user_id', 'external_redirect_url',
        'completed_at'
    ];

    protected $hidden = [
        'external_id'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function courseAttendance()
    {
        return $this->hasOne(CourseAttendance::class);
    }
}
