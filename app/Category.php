<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static findOrFail(int $categoryId)
 * @method static Category create(array $data)
 * @property string about
 * @property int|null uidata_image_id
 * @property string uidata_color
 * @property FileInfo|null image
 */
class Category extends Model
{
    public $timestamps = false;

    use SoftDeletes;

    protected $fillable = [
        'name', 'about', 'uidata_image_id', 'uidata_color'
    ];

    public function image() {
        return $this->belongsTo(FileInfo::class, 'uidata_image_id');
    }

    public function courses() {
        return $this->belongsToMany(Course::class, 'course_categories');
    }
}
