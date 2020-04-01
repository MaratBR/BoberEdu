<?php


namespace App;


use Carbon\Carbon;
use DateTimeInterface;

trait Iso8601Serialization
{
    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return Carbon::instance($date)->toIso8601String();
    }
}
