<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

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
 * @property string name
 * @property float price
 * @property bool available
 * @property int trial_length
 * @property string about
 * @property Carbon sign_up_beg
 * @property Carbon sign_up_end
 */
class Course extends Model
{
    use SoftDeletes;

    public static $rules = [
        'name' => 'required|min:1|max:255',
        'price' => 'numeric|min:0|max:9999999999999999999.99',
        'about' => 'required',
        'sign_up_beg' => 'nullable|date_format:c',
        'sign_up_end' => 'nullable|date_format:c',
        'available' => 'boolean'
    ];

    protected $fillable = [
        'name', 'price', 'about', 'sign_up_beg', 'sign_up_end',
        'available', 'trial_length', 'category_id'
    ];
    protected $casts = [
        'available' => 'boolean',
        'price' => 'float',
        'sign_up_beg' => 'datetime:Y-m-d',
        'sign_up_end' => 'datetime:Y-m-d',
    ];
    protected $hidden = [
        'deleted_at'
    ];
    protected $dates = [
        'deleted_at', 'created_at', 'updated_at',
        'sign_up_beg', 'sign_up_end'
    ];

    public function units() {
        return $this->hasMany(Unit::class);
    }

    public function teachers() {
        // TODO
        return $this->belongsToMany(Teacher::class, 'teaching_assignments');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function rating() {
        return $this->hasMany(Rate::class);
    }

    public function canBePurchased()
    {
        $now = Carbon::now();
        return ($this->sign_up_beg == null && $this->sign_up_end == null) ||
            ($this->sign_up_beg < $now && $this->sign_up_end > $now);
    }
}
