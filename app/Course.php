<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * @method static Course create(array $data)
 * @method static Course findOrFail(int $id)
 * @property Unit[] units
 */
class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'price', 'about', 'sign_up_beg', 'sign_up_end',
        'available'
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


    public static $rules = [
        'name' => 'required|min:1|max:255',
        'price' => 'numeric|min:0|max:9999999999999999999.99',
        'about' => 'required',
        'sign_up_beg' => 'nullable|date_format:c',
        'sign_up_end' => 'nullable|date_format:c',
        'available' => 'boolean'
    ];

    public static $updateRules = [
        'name' => 'min:1|max:255',
        'price' => 'numeric|min:0|max:9999999999999999999.99',
        'about' => 'string',
        'sign_up_beg' => 'nullable|date_format:c',
        'sign_up_end' => 'nullable|date_format:c',
        'available' => 'boolean'
    ];

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }
}
