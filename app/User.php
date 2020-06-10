<?php

namespace App;

use App\Utils\Audit\IDisplayName;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;
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
class User extends Authenticatable implements JWTSubject, IDisplayName
{
    use Notifiable, HasApiTokens, Searchable;

    protected $fillable = [
        'name', 'email', 'password', 'display_name', 'status', 'normalized_name', 'normalized_email', 'about',
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
        'is_admin' => 'boolean',
        'activated' => 'boolean'
    ];

    public static function boot()
    {
        parent::boot();

        $callback = function ($user) {
            $user->normalized_email = strtoupper($user->email);
            $user->normalized_name = strtoupper($user->name);
        };

        self::updating($callback);
        self::creating($callback);
    }

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'display_name' => $this->display_name,
            'email' => $this->email
        ];
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, Enrollment::class)
            ->wherePivot('deleted_at', '!=', null);
    }

    public function avatar()
    {
        return $this->belongsTo(FileInfo::class, 'avatar_id');
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'user_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
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

    function getDisplayName(): string
    {
        return $this->name . ($this->display_name ? ' (' . $this->display_name . ')' : '');
    }
}
