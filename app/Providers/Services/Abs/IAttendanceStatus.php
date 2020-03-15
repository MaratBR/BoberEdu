<?php

namespace App\Providers\Services\Abs;

use Illuminate\Contracts\Support\Jsonable;
const ATTENDANCE_STATUS_YES = 'yes'; // user has access to course
const ATTENDANCE_STATUS_NO = 'no'; // user does not have access
const ATTENDANCE_STATUS_CANCELLED = 'cancelled'; // user tried to pay for course but payment get cancelled
const ATTENDANCE_STATUS_AWAITING_PAYMENT = 'awaiting_payment'; // no payment created yet
const ATTENDANCE_STATUS_PREVIEW = 'preview'; // user has access to course due to preview period
const ATTENDANCE_STATUS_PREVIEW_EXPIRED = 'preview_expired'; // self-explanatory

interface IAttendanceStatus extends Jsonable
{
    /**
     * Returns true if user has access to the course.
     * Due to preview period or purchase.
     *
     * @return bool
     */
    function hasAccess(): bool;

    /**
     * Returns true if attendance is marked as preview.
     * Returns true even if preview period has ended.
     *
     * @return bool
     */
    function isPreview(): bool;

    /**
     * Returns true if attendance record is preview and
     * preview period has ended
     *
     * @return bool
     */
    function isExpired(): bool;

    /**
     * Returns string representation of the status
     *
     * @return string
     */
    function asString(): string;

    /**
     * Return status of purchase if present, null otherwise
     *
     * @return string|null
     */
    function getPurchaseInnerStatus(): ?string;

    /**
     * Returns true if associated payment exists
     * (even if it has been cancelled or failed)
     *
     * @return bool
     */
    function hasPayment(): bool;

    /**
     * Returns true is payment is pending
     *
     * @return bool
     */
    function isPaymentPending(): bool;

    /**
     * Returns true is payment is successful
     *
     * @return bool
     */
    function isPaymentSuccessful(): bool;

    /**
     * Returns true is user still can pay for a course e.g. payment is not expired.
     * Can't return false is hasPayment returns false
     *
     * @return bool
     */
    function isAwaitingPayment(): bool;

    /**
     * Return false is course is not found e.g. status of
     * non-existing CourseAttendance. Normally this method should always return true.
     *
     * @return bool
     */
    function exists(): bool;
}
