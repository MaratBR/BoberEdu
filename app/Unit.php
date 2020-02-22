<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Unit extends Model
{
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
        'order_num' => 'integer'
    ];

    public static $updateRules = [
        'name' => 'min:1|max:255',
        'is_preview' => 'boolean',
        'about' => 'string',
        'order_num' => 'integer'
    ];

    public static function get(int $courseId, int $unitId)
    {
        return self::query()
            ->where('id', '=', $unitId)
            ->where('course_id', '=', $courseId)
            ->whereHas('course', function (Builder $q) {
                $q->where('available', '');
            });
    }
}
