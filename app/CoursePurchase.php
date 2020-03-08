<?php

namespace App;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail($paymentId)
 */
class CoursePurchase extends Model
{
    use HasTimestamps;

    protected $fillable = [
        'price', 'user_id', 'user_agent', 'ip', 'payment_external_id',
        'preview'
    ];

    protected $dates = [
        'created_at'
    ];
}
