<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TeacherApprovalForm
 * @package App
 * @property string full_name
 * @property string education
 * @property string degree
 * @property string location
 * @property string extra
 * @property int admin_id
 * @property int user_id
 * @property User user
 * @property User|null admin
 * @property bool approved
 * @method static findOrFail(int $id)
 * @method static create(array $data)
 */
class TeacherApprovalForm extends Model
{
    protected $fillable = [
        'full_name', 'education', 'degree', 'location', 'extra', 'approved', 'user_id', 'admin_id'
    ];

    protected $casts = [
        'approved' => 'bool'
    ];

    public static function awaitingReview()
    {
        return self::query()->whereNull('approved');
    }

    public static function approved()
    {
        return self::query()->where('approved', '=', true);
    }

    public static function rejected()
    {
        return self::query()->where('approved', '=', false);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    function getTeacherPayload()
    {
        return [
            'full_name' => $this->full_name,
            'education' => $this->education,
            'location' => $this->location,
            'about' => 'I am your teacher!'
        ];
    }

}
