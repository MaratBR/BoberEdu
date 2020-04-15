<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * @property Carbon expires_at
 * @property Uuid id
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
 */
class Payment extends Model
{
    public const STATUS_SUCCESSFUL = 'successful';
    public const STATUS_PENDING = 'pending';
    public const STATUS_CANCELLED = 'cancelled';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 'completed_at', 'status',
        'uid', 'gateaway_name', 'title', 'user_agent', 'ip_address',
        'redirect_url', 'user_id', 'completed_at', 'expires_at', 'amount'
    ];

    public function getIsSuccessfulAttribute(): bool {
        return $this->status == self::STATUS_SUCCESSFUL;
    }

    public function getIsPendingAttribute(): bool {
        return $this->status == self::STATUS_PENDING;
    }

    public function getIsCancelledAttribute(): bool {
        return $this->status == self::STATUS_CANCELLED;
    }

    public function getIsFailedAttribute(): bool {
        return !$this->is_successful && !$this->is_pending;
    }

    public function getNormalizedGateawayNameAttribute(): string {
        return strtoupper(str_replace('_', ' ', $this->gateaway_name));
    }

    protected $dates = ['completed_at', 'expires_at'];
}
