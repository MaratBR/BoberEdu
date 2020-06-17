<?php

namespace App\Models;

use App\Utils\Audit\IDisplayName;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Laravel\Scout\Searchable;

/**
 * @method static Course create(array $data)
 * @method static Course findOrFail(int $id)
 * @method static Builder where(string $column, string $op, $value)
 * @method static Course|null find(int $id)
 *
 * @property Unit[] units
 * @property Category category
 *
 * @property int id
 * @property int|null image_id
 * @property string name
 * @property float price
 * @property bool available
 * @property int trial_length
 * @property int lessons_count
 * @property int units_count
 * @property string about
 * @property string summary
 * @property Carbon sign_up_beg
 * @property Carbon sign_up_end
 * @property FileInfo|null image
 */
class Course extends Model implements IDisplayName
{
    use SoftDeletes, Searchable;

    protected $fillable = [
        'name', 'price', 'about', 'sign_up_beg', 'sign_up_end',
        'available', 'trial_length', 'category_id', 'summary', 'image_id'
    ];

    protected $casts = [
        'available' => 'boolean',
        'price' => 'float',
        'sign_up_beg' => 'datetime:Y-m-d',
        'sign_up_end' => 'datetime:Y-m-d',
    ];

    protected $dates = [
        'deleted_at', 'created_at', 'updated_at',
        'sign_up_beg', 'sign_up_end'
    ];

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'name_exact' => $this->name,
            'about' => $this->about,
            'summary' => $this->summary,
            'tags' => '' // TODO
        ];
    }

    public function getUnitsCountAttribute()
    {
        return $this->units()->count();
    }

    public function units()
    {
        return $this->hasMany(Unit::class)->orderBy('order_num');
    }

    public function getLessonsCountAttribute()
    {
        return $this->lessons()->count();
    }

    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, Unit::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teaching_assignments');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function rating()
    {
        return $this->hasMany(Rate::class);
    }

    public function image()
    {
        return $this->belongsTo(FileInfo::class, 'image_id');
    }

    public function canBePurchased()
    {
        $now = Carbon::now();
        return ($this->sign_up_beg == null && $this->sign_up_end == null) ||
            ($this->sign_up_beg < $now && $this->sign_up_end > $now);
    }

    function getDisplayName(): string
    {
        return $this->name;
    }
}
