<?php

namespace App;

use App\Http\DTO\TeacherDto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @method static AuditRecord create(array $array)
 */
class AuditRecord extends Model
{
    public $timestamps = false;

    public const A_COURSE_EDIT = 'Ce';
    public const A_COURSE_DELETE = 'Cd';
    public const A_COURSE_RESTORED = 'Cr';
    public const A_USER_PROMOTE = 'Up';
    public const A_USER_DEMOTE = 'Ud';

    public const A_TEACHER_ASSIGN = 'Ta';
    public const A_TEACHER_CREATED = 'Tc';
    public const A_TEACHER_UPDATE = 'Tu';
    public const A_TEACHER_REVOKE = 'Tr';

    protected $fillable = [
        'action', 'extra', 'subject', 'ip', 'user_id'
    ];

    public static function teacherCreated(User $actor, Request $request, User $user, Teacher $teacher): AuditRecord
    {
        return self::new($actor, $request, self::A_TEACHER_CREATED, (string)$teacher->id, [
            'u' => $user->id
        ]);
    }

    public static function teacherUpdate(User $actor, Request $request, Teacher $teacher, array $changed): AuditRecord
    {
        return self::new($actor, $request, self::A_TEACHER_UPDATE, (string)$teacher->id, [
            'f' => $changed
        ]);
    }


    public static function userPromoted(User $user, Request $request, int $userId, string $roleName, string $reason): AuditRecord
    {
        return self::new($user, $request, self::A_USER_PROMOTE, (string)$userId, [
            'd' => [$roleName, $reason]
        ]);
    }

    public static function userDemoted(User $user, Request $request, int $userId, string $roleName, string $reason): AuditRecord
    {
        return self::new($user, $request, self::A_USER_DEMOTE, (string)$userId, [
            'd' => [$roleName, $reason]
        ]);
    }

    public static function courseEdited(User $user, Request $request, Course $course, array $changed): AuditRecord
    {
        return self::new($user, $request, self::A_COURSE_EDIT, $course->id, [
            'f' => $changed
        ]);
    }

    public static function courseDeleted(User $user, Request $request, Course $course, string $reason): AuditRecord
    {
        return self::new($user, $request, self::A_COURSE_DELETE, (string)$course->id, [
            'r' => $reason
        ]);
    }

    private static function new(User $user, Request $request, string $action, ?string $subject = null, ?array $extra = null): AuditRecord
    {
        return AuditRecord::create([
            'user_id' => $user->id,
            'ip' => $request->ip(),
            'action' => $action,
            'subject' => $subject,
            'extra' => $extra
        ]);
    }
}
