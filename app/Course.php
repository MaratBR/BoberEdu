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
        'name', 'price', 'about', 'sign_in_beg', 'sign_in_end',
        'available'
    ];

    protected $casts = [
        'sign_in_beg' => 'date',
        'sign_in_end' => 'date',
        'available' => 'boolean',
        'price' => 'float'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public static $rules = [
        'name' => 'required|min:1|max:255',
        'price' => 'number|default:0|min:0|max:9999999999999999999.99',
        'about' => 'required',
        'sign_up_beg' => 'nullable|date',
        'sign_up_end' => 'nullable|date',
        'available' => 'boolean'
    ];

    public static $updateRules = [
        'name' => 'min:1|max:255',
        'price' => 'number|min:0|max:9999999999999999999.99',
        'about' => 'string',
        'sign_up_beg' => 'nullable|date',
        'sign_up_end' => 'nullable|date',
        'available' => 'boolean'
    ];

    public static function getById($id): Course
    {
        return self::findOrFail($id);
    }

    public function scopeForUser(Builder $query, User $user)
    {
        if ($user->can(Course::class, 'bypass_protection')) {
            return $query;
        }
        return $query
            ->where('available', '=', true)
            ->;
    }
}
