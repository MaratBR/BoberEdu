<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @method static User create(array $data)
 * @method static User findOrFail(int|null $id)
 * @property Role[] roles
 * @property int id
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'sex', 'status'
    ];

    protected $appends = [
        'roles_names'
    ];

    public function getRolesNamesAttribute() {
        $names = [];
        foreach ($this->roles as $role) {
            $names[] = $role->name;
        }
        return $names;
    }

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

    public function roles() {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function courses() {
        return $this->belongsToMany(Course::class, CourseAttendance::class);
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

        return TeachingPeriod::query()
            ->where('course_id', '=', $course)
            ->where('since', '>', DB::raw('NOW()'))
            ->whereNull('until')
            ->orWhere('until', '>', DB::raw('NOW()'))
            ->exists();
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
