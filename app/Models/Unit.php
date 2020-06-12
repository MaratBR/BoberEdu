<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * @method static Unit create(array $array)
 * @method static Unit findOrFail(int $unitId)
 * @property Course course
 * @property int course_id
 * @property string name
 * @property string about
 * @property int id
 * @property bool preview
 * @property Lesson[] lessons
 */
class Unit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'preview', 'about', 'course_id',
        'order_num'
    ];
    protected $hidden = [
        'deleted_at'
    ];
    protected $casts = [
        'order_num' => 'integer',
        'preview' => 'boolean'
    ];

    public static function scopeForUser(User $user)
    {
        if ($user->isAdmin())
            return self::query();
        return self::query()
            ->whereHas('course', function (Builder $q) {
                $q->where('available', '=', true);
            });
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
