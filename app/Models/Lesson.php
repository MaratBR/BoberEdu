<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 * @method static findOrFail(int $id)
 * @property Unit unit
 * @property string title
 * @property string content
 * @property int id
 * @property string summary
 */
class Lesson extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'content', 'unit_id', 'order_num', 'summary'
    ];
    protected $hidden = [
        'deleted_at'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
