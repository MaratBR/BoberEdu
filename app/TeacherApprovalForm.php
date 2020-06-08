<?php

namespace App;

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
 * @method static findOrFail(int $id)
 * @method static create(array $data)
 */
class TeacherApprovalForm extends Model
{
    protected $fillable = [
        'full_name', 'education', 'degree', 'location', 'extra', 'approved', 'user_id', 'admin_id'
    ];

    public function admin() {
        return $this->belongsTo(User::class, 'admin_id');
    }


    public function user() {
        return $this->belongsTo(User::class);
    }
}
