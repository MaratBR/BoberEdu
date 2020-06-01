<?php


namespace App\Utils\Audit;


use Illuminate\Database\Eloquent\Model;

class Audit
{
    private const TEACHER_PREFIX = 1 << 16;

    public const UPDATE = 1;
    public const CREATE = 2;
    public const DELETE = 3;
    public const RESTORE = 4;

    public const PROMOTE = 10;
    public const DEMOTE = 11;
    public const BLOCK = 12;
    public const UPLOAD_AVATAR= 13;

    public const ASSIGN_TEACHER = 20;
    public const REVOKE_TEACHER = 21;

    public static function subjectId($subject) {
        if ($subject instanceof Model) {
            return strval($subject->getKey());
        } else {
            return strval($subject);
        }
    }

    public static function subjectType($subject) {
        if ($subject instanceof Model) {
            return class_basename($subject);
        } else {
            return null;
        }
    }

}
