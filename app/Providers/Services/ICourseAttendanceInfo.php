<?php


namespace App\Providers\Services;


interface ICourseAttendanceInfo
{
    function isPreview(): bool;
    function giftTo(): ?int;

    const DEFAULT_IS_PREVIEW = false;
}
