<?php

namespace App\Providers\Services\Abs;

const ATTENDANCE_STATUS_YES = 'yes'; // user has access to course
const ATTENDANCE_STATUS_NO = 'no'; // user does not have access
const ATTENDANCE_STATUS_CANCELLED = 'cancelled'; // user tried to pay for course but payment get cancelled
const ATTENDANCE_STATUS_AWAITING_PAYMENT = 'awaiting_payment'; // no payment created yet
const ATTENDANCE_STATUS_PREVIEW = 'preview'; // user has access to course due to preview period
const ATTENDANCE_STATUS_PREVIEW_EXPIRED = 'preview_expired'; // self-explanatory

interface IAttendanceStatus
{
    function hasAccess(): bool;

    function isPreview(): bool;

    function isExpired(): bool;

    function asString(): string;

    function getPurchaseInnerStatus(): ?string;

    function hasPayment(): bool;

    function courseExists(): bool;
}
