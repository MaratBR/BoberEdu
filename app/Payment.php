<?php

namespace App;

use Carbon\Carbon;
use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * @property Carbon expires_at
 * @property string id
 * @property int user_id
 * @property string uid
 * @property Carbon completed_at
 * @property string gateaway_name
 * @property string title
 * @property string redirect_url
 *
 * @property string normalized_gateaway_name
 * @property Carbon created_at
 * @property string status
 * @property bool is_pending
 * @property bool is_cancelled
 * @property bool is_successful
 * @property bool is_failed
 * @property string user_agent
 * @property string ip_address
 */
class Payment extends Model
{
    public const STATUS_SUCCESSFUL = 'successful';
    public const STATUS_PENDING = 'pending';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_PREPARED = 'prepared';

    protected $fillable = [
        'completed_at', 'status', 'id',
        'uid', 'gateaway_name', 'title', 'user_agent', 'ip_address',
        'redirect_url', 'user_id', 'completed_at', 'expires_at', 'amount'
    ];
    protected $dates = ['completed_at', 'expires_at'];
    protected $keyType = 'string';
    public $incrementing = false;

    public function getIsSuccessfulAttribute(): bool
    {
        return $this->status == self::STATUS_SUCCESSFUL;
    }

    public function getIsPendingAttribute(): bool
    {
        return $this->status == self::STATUS_PENDING;
    }

    public function getIsCancelledAttribute(): bool
    {
        return $this->status == self::STATUS_CANCELLED;
    }

    public function getIsFailedAttribute(): bool
    {
        return !$this->is_successful && !$this->is_pending;
    }

    public function getNormalizedGateawayNameAttribute(): string
    {
        return strtolower(str_replace('_', ' ', $this->gateaway_name));
    }
}
