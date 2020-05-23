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
 * @property Role[] roles
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
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'password', 'display_name', 'status','normalized_name', 'normalized_email', 'about',
        'avatar_id'
    ];
    protected $appends = [
        'roles_names'
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

    public function getRolesNamesAttribute()
    {
        $names = [];
        foreach ($this->roles as $role) {
            $names[] = $role->name;
        }
        return $names;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, Enrollment::class)
            ->wherePivot('deleted_at', '!=', null);
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
        return $this->hasRole('admin');
    }

    public function hasRole(string $name): bool
    {
        foreach ($this->roles as $role) {
            if (!$role)
                continue;
            if ($role->name === $name) {
                return true;
            }
        }

        return false;
    }

    public function addRole($role)
    {
        if (is_string($role))
            $role = Role::ensure($role);

        if ($role)
            $this->attachRole($role);
    }

    public function ensureRole($role)
    {
        if (is_string($role))
            $role = Role::ensure($role);

        $this->attachRole($role);
    }

    private function attachRole(Role $role)
    {
        $this->roles()->attach($role);
    }
}
