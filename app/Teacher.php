<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static Teacher findOrFail(int $id)
 * @method static Teacher create(array $data)
 * @property int id
 * @property string full_name
 * @property string passport_num
 * @property int user_id
 * @property int|null avatar_id
 * @property FileInfo|null avatar
 * @property User user
 */
class Teacher extends Model
{
    protected $fillable = [
        'full_name', 'passport_num', 'user_id', 'avatar_id', 'about',

        'link_web', 'link_yt', 'link_linked_in', 'link_vk', 'link_fb', 'link_twitter'
    ];

    function avatar() {
        return $this->belongsTo(FileInfo::class);
    }

    function user() {
        return $this->belongsTo(User::class);
    }
}
