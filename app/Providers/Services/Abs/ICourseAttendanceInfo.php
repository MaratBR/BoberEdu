<?php


namespace App\Providers\Services\Abs;


interface ICourseAttendanceInfo
{
    function isPreview(): bool;
    function giftTo(): ?int;

    const DEFAULT_IS_PREVIEW = false;
}
