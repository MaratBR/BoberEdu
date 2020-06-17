<?php

namespace App\Services\Implementation;

use App\Models\Enrollment;
use App\Purchase;
use App\Services\Abs\IAttendanceStatus;
use Carbon\Carbon;
use const App\Services\Abs\ATTENDANCE_STATUS_AWAITING_PAYMENT;
use const App\Services\Abs\ATTENDANCE_STATUS_CANCELLED;
use const App\Services\Abs\ATTENDANCE_STATUS_NO;
use const App\Services\Abs\ATTENDANCE_STATUS_PREVIEW;
use const App\Services\Abs\ATTENDANCE_STATUS_PREVIEW_EXPIRED;
use const App\Services\Abs\ATTENDANCE_STATUS_YES;


class AttendanceStatus implements IAttendanceStatus
{
    private $record;
    private $previewDurationInDays;

    public function __construct(?Enrollment $record, int $previewDurationInDays)
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

    public function exists(): bool
    {
        return $this->record !== null;
    }

    function isExpired(): bool
    {
        return $this->exists() && $this->addPreviewPeriod($this->record->created_at)->gt(Carbon::now());
    }

    private function addPreviewPeriod(Carbon $createdAt): Carbon
    {
        return $createdAt->addDays($this->previewDurationInDays);
    }

    function getPurchaseInnerStatus(): ?string
    {
        return $this->hasPayment() ?
            $this->record->purchase->status :
            null;
    }

    public function hasPayment(): bool
    {
        return $this->exists() && $this->attendance->purchase_id !== null;
    }

    public function isPaymentSuccessful(): bool
    {
        return $this->hasPayment() && $this->getPurchaseInnerStatus() === Purchase::STATUS_SUCCESSFUL;
    }

    public function isAwaitingPayment(): bool
    {
        return $this->isPaymentPending();
    }

    public function isPaymentPending(): bool
    {
        return $this->hasPayment() && $this->getPurchaseInnerStatus() === Purchase::STATUS_PENDING;
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
}
