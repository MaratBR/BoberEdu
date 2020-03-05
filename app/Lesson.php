<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 */
class Lesson extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'content', 'unit_id', 'order_num'
    ];

    protected $hidden = [
        'deleted_at'
    ];

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

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
