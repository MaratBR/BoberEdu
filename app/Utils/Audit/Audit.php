<?php


namespace App\Utils\Audit;


class Audit
{
    private const TEACHER_PREFIX = 1 << 16;

    public const NEW_TEACHER = self::TEACHER_PREFIX | 1;
    public const TEACHER_ASSIGNED = 'Tassign';
    public const TEACHER_REVOKED = 'Trevoke';
    public const TEACHER_NEW = 'Tnew';
    const TEACHER_DELETED = 'Tdel';
    public const TEACHER_UPDATED = 'Tupd';

    public const LESSON_UPDATE = 'Lupd';
    public const LESSON_CREATE = 'Lnew';
    public const LESSON_DELETE = 'Ldel';

    const USER_UPDATE = 'Uupd';
    const USER_NEW = 'Unew';
    const USER_BLOCK = 'Ublock';

}
