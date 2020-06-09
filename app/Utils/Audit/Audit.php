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
    public const APPROVE_TEACHER = 22;
    public const DISAPPROVE_TEACHER = 23;
    public const UPDATE_COURSE_UNITS = 30;

    private static $assoc = null;

    private static function getValuesAssociationTable(): array {
        if (self::$assoc == null) {
            self::$assoc = [];

            $reflect = new \ReflectionClass(self::class);
            $constants = $reflect->getConstants();

            foreach ($constants as $name => $constantValue) {
                self::$assoc[$constantValue] = $name;
            }
        }

        return self::$assoc;
    }

    public static function getTypeRepresentation(int $value)
    {
        return self::getValuesAssociationTable()[$value] ?? "custom" . $value;
    }

    public static function subjectId($subject) {
        if ($subject instanceof Model) {
            return strval($subject->getKey());
        } else {
            return strval($subject);
        }
    }

    public static function subjectType($subject) {
        if ($subject instanceof Model) {
            return $subject->getTable();
        } else {
            return null;
        }
    }
}


