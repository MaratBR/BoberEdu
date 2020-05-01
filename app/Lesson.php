<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 * @method static findOrFail(int $id)
 * @property Unit unit
 * @property string title
 * @property string content
 */
class Lesson extends Model
{
    use SoftDeletes;

    public static $rules = [
        'title' => 'required|string|min:1|max:255',
        'content' => 'string|required',
        'unit_id' => 'integer|required',
        'order_num' => 'integer|required|min:0|max:10000'
    ];
    public static $updateRules = [
        'title' => 'string|min:1|max:255',
        'content' => 'string',
        'order_num' => 'integer|min:0|max:10000'
    ];
    protected $fillable = [
        'title', 'content', 'unit_id', 'order_num'
    ];
    protected $hidden = [
        'deleted_at'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
