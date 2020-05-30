<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @method static User create(array $data)
 * @method static User findOrFail(int|null $id)
 *
 * @property boolean is_admin
 * @property int id
 * @property Carbon created_at
 * @property string|null display_name
 * @property string name
 * @property string normalized_name
 * @property string normalized_email
 * @property string email
 * @property string status
 * @property string about
 * @property int|null avatar_id
 * @property FileInfo|null avatar
 * @property Teacher|null teacher
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'password', 'display_name', 'status','normalized_name', 'normalized_email', 'about',
        'avatar_id', 'is_admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'deleted_at', 'roles'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, Enrollment::class)
            ->wherePivot('deleted_at', '!=', null);
    }

    public function avatar() {
        return $this->belongsTo(FileInfo::class, 'avatar_id');
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'user_id');
    }

    /**
     * @inheritDoc
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @inheritDoc
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }
}
