<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static findOrFail(int $categoryId)
 */
class Category extends Model
{
    public $timestamps = false;

    use SoftDeletes;

    protected $fillable = [
        'name', 'about'
    ];

    public function courses() {
        return $this->belongsToMany(Course::class, 'course_categories');
    }
}
