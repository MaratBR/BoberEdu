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
 */
class Purchase extends Model
{
    const STATUS_SUCCESSFUL = 'successful';
    const STATUS_PENDING = 'pending';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    public $timestamps = false;

    protected $fillable = [
        'price', 'external_id',
        'status', 'user_id', 'external_redirect_url'
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