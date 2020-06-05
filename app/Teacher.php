<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

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
 * @property string about
 * @property string link_yt
 * @property string link_vk
 * @property string link_fb
 * @property string link_web
 * @property string link_twitter
 * @property string link_linked_in
 */
class Teacher extends Model
{
    use SoftDeletes, Searchable;

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

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'teaching_assignments');
    }
}
