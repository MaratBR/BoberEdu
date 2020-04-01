<?php

namespace App\Providers\Services;

use App\Providers\Services\Abs\IAttendanceStatus;
use App\Purchase;
use App\UserCourse;

use const App\Providers\Services\Abs\ATTENDANCE_STATUS_AWAITING_PAYMENT;
use const App\Providers\Services\Abs\ATTENDANCE_STATUS_CANCELLED;
use const App\Providers\Services\Abs\ATTENDANCE_STATUS_NO;
use const App\Providers\Services\Abs\ATTENDANCE_STATUS_PREVIEW;
use const App\Providers\Services\Abs\ATTENDANCE_STATUS_PREVIEW_EXPIRED;
use const App\Providers\Services\Abs\ATTENDANCE_STATUS_YES;

use Carbon\Carbon;


class AttendanceStatus implements IAttendanceStatus
{
    private $record;
    private $previewDurationInDays;

    public function __construct(?UserCourse $record, int $previewDurationInDays)
    {
        $this->record = $record;
        $this->previewDurationInDays = $previewDurationInDays;
    }

    function hasAccess(): bool
    {
        return ($this->isPreview() && !$this->isExpired()) ||
            $this->getPurchaseInnerStatus() === Purchase::STATUS_SUCCESSFUL;
    }

    function isPreview(): bool
    {
        return $this->exists() && $this->record->preview;
    }

    function isExpired(): bool
    {
        return $this->exists() && $this->addPreviewPeriod($this->record->created_at)->gt(Carbon::now());
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
            $this->record->purchase->status :
            null;
    }

    private function addPreviewPeriod(Carbon $createdAt): Carbon
    {
        return $createdAt->addDays($this->previewDurationInDays);
    }

    public function exists(): bool
    {
        return $this->record !== null;
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
            'user_id' => $this->exists() ? $this->record->user_id : null,
        ]);
    }
}
