<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * @method static Course create(array $data)
 * @method static Course findOrFail(int $id)
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
        'price' => 'float'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public static $rules = [
        'name' => 'required|min:1|max:255',
        'price' => 'numeric|min:0|max:9999999999999999999.99',
        'about' => 'required',
        'sign_up_beg' => 'nullable|date',
        'sign_up_end' => 'nullable|date',
        'available' => 'boolean'
    ];

    public static $updateRules = [
        'name' => 'min:1|max:255',
        'price' => 'numeric|min:0|max:9999999999999999999.99',
        'about' => 'string',
        'sign_up_beg' => 'nullable|date_format:Ymd',
        'sign_up_end' => 'nullable|date_format:Ymd',
        'available' => 'boolean'
    ];

    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
