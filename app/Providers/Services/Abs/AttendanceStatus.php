<?php

namespace App\Providers\Services\Abs;

use App\CourseAttendance;
use App\Providers\Services\Abs\IAttendanceStatus;
use App\Purchase;
use Carbon\Carbon;

class AttendanceStatus implements IAttendanceStatus
{
    private $attendance;
    private $previewDurationInDays;

    public function __construct(?CourseAttendance $attendance, int $previewDurationInDays)
    {
        $this->attendance = $attendance;
        $this->previewDurationInDays = $previewDurationInDays;
    }

    function hasAccess(): bool
    {
        return ($this->isPreview() && !$this->isExpired()) ||
            $this->getPurchaseInnerStatus() === Purchase::STATUS_SUCCESSFUL;
    }

    function isPreview(): bool
    {
        return $this->courseExists() && $this->attendance->preview;
    }

    function isExpired(): bool
    {
        return $this->courseExists() && $this->addPreviewPeriod($this->attendance->created_at)->gt(Carbon::now());
    }

    function asString(): string
    {
        if (!$this->courseExists())
            return ATTENDANCE_STATUS_NO;
        elseif ($this->isPreview())
            if ($this->isExpired())
                return ATTENDANCE_STATUS_PREVIEW_EXPIRED;
            else
                return ATTENDANCE_STATUS_PREVIEW;
        else {
            $innerStatus = $this->getPurchaseInnerStatus();

            switch ($innerStatus) {
                case null:
                case Purchase::STATUS_PENDING:
                    return ATTENDANCE_STATUS_AWAITING_PAYMENT;
                case Purchase::STATUS_SUCCESSFUL:
                    return ATTENDANCE_STATUS_YES;
                default:
                    return ATTENDANCE_STATUS_CANCELLED;
            }

        }
    }

    function getPurchaseInnerStatus(): ?string
    {
        return $this->courseExists() ?
            ($this->attendance->purchase ? $this->attendance->purchase->status : null) :
            null;
    }

    private function addPreviewPeriod(Carbon $createdAt): Carbon
    {
        return $createdAt->addDays($this->previewDurationInDays);
    }

    public function courseExists(): bool
    {
        return $this->attendance !== null;
    }

    public function hasPayment(): bool
    {
        return $this->getPurchaseInnerStatus() === Purchase::STATUS_PENDING;
    }
}
