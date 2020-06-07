<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * @method static findOrFail(int $categoryId)
 * @method static Category create(array $data)
 * @property string about
 * @property int courses_count
 * @property int students_count
 * @property int|null uidata_image_id
 * @property string uidata_color
 * @property FileInfo|null image
 */
class Category extends Model
{
    use HasRelationships;
    public $timestamps = false;

    use SoftDeletes;

    protected $fillable = [
        'name', 'about', 'uidata_image_id', 'uidata_color'
    ];

    public function image() {
        return $this->belongsTo(FileInfo::class, 'uidata_image_id');
    }

    public function courses() {
        return $this->hasMany(Course::class);
    }

    public function students() {
        return $this->hasManyDeep(User::class, [Course::class, 'enrollments'])
            ->where('enrollments.activated', '=', true)
            ->whereNull('enrollments.deleted_at');
    }

    public function getCoursesCountAttribute() {
        return $this->courses()->count();
    }

    public function getStudentsCountAttribute() {
        return $this->students()->count();
    }
}
