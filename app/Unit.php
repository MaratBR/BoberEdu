<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * @method static create(array $array)
 * @property Course course
 * @property int course_id
 */
class Unit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'is_preview', 'about', 'course_id',
        'order_num'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    protected $casts = [
        'order_num' => 'integer',
        'is_preview' => 'boolean'
    ];

    public static $rules = [
        'name' => 'required|min:1|max:255',
        'is_preview' => 'required|boolean',
        'about' => 'required',
        'order_num' => 'integer',
        'course_id' => 'integer|required'
    ];

    public static $updateRules = [
        'name' => 'min:1|max:255',
        'is_preview' => 'boolean',
        'about' => 'string',
        'order_num' => 'integer'
    ];

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public static function scopeForUser(User $user)
    {
        if ($user->isAdmin())
            return self::query();
        return self::query()
            ->whereHas('course', function (Builder $q) {
                $q->where('available', '=', true);
            });
    }
}
