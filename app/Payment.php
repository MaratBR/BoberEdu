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
 * @property bool is_successful
 * @property bool is_pending
 * @property string gateaway_name
 * @property string title
 * @property string redirect_url
 *
 * @property bool is_expired
 * @property string normalized_gateaway_name
 * @property Carbon created_at
 */
class Payment extends Model
{
    protected $fillable = [
        'completed_at', 'is_successful', 'is_pending',
        'uid', 'gateaway_name', 'title', 'user_agent', 'ip_address',
        'redirect_url', 'user_id', 'completed_at', 'expires_at', 'amount'
    ];

    public function getIsExpiredAttribute(): bool {
        return $this->expires_at && $this->expires_at > Carbon::now('UTC');
    }

    public function getNormalizedGateawayNameAttribute(): string {
        return strtoupper(str_replace('_', ' ', $this->gateaway_name));
    }

    protected $dates = ['completed_at', 'expires_at'];
}
