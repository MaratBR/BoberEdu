<?php


namespace App\Utils\Audit;


class Audit
{
    private const TEACHER_PREFIX = 1 << 16;

    public const NEW_TEACHER = self::TEACHER_PREFIX | 1;
    public const TEACHER_ASSIGNED = 'Tassign';
    public const TEACHER_REVOKED = 'Trevoke';
    public const TEACHER_NEW = 'Tnew';
    public const TEACHER_UPDATED = 'Tupd';

}
