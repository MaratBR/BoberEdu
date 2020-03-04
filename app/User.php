<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @method static User create(array $credentials)
 * @property Role[] roles
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'sex'
    ];

    public static $updateRules = [
        'name' => 'string|max:255|min:1',
        'email' => 'email',
        'sex' => 'in:u,f,m'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles() {
        return $this->belongsToMany('App\Role', 'user_roles');
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


    /**
     * @param $course int|Course
     * @return bool
     */
    public function teacherAt($course): bool {
        if ($course instanceof Course)
            $course = $course->getKey();
        // TODO Implement
        return true;
    }

    private function hasRole(string $name): bool
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

}
