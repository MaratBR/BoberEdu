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
        return $this->exists() && $this->attendance->preview;
    }

    function isExpired(): bool
    {
        return $this->exists() && $this->addPreviewPeriod($this->attendance->created_at)->gt(Carbon::now());
    }

    function asString(): string
    {
        if (!$this->exists())
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
        return $this->hasPayment() ?
            $this->attendance->purchase->status :
            null;
    }

    private function addPreviewPeriod(Carbon $createdAt): Carbon
    {
        return $createdAt->addDays($this->previewDurationInDays);
    }

    public function exists(): bool
    {
        return $this->attendance !== null;
    }

    public function hasPayment(): bool
    {
        return $this->exists() && $this->attendance->purchase_id !== null;
    }

    public function isPaymentPending(): bool
    {
        return $this->hasPayment() && $this->getPurchaseInnerStatus() === Purchase::STATUS_PENDING;
    }

    public function isPaymentSuccessful(): bool
    {
        return $this->hasPayment() && $this->getPurchaseInnerStatus() === Purchase::STATUS_SUCCESSFUL;
    }

    public function isAwaitingPayment(): bool
    {
        return $this->isPaymentPending();
    }

    /**
     * @inheritDoc
     */
    public function toJson($options = 0)
    {
        return json_encode([
            'status' => $this->asString(),
            'gifted_by' => $this->exists() ? $this->attendance->gifted_by_id : null,
            'gift_to' => $this->exists() && $this->attendance->gifted_by_id ? $this->attendance->user_id : null
        ]);
    }
}
