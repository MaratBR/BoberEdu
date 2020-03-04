<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class DateEx implements Rule
{
    private $fmtEx;

    public const ISO = 2;

    /**
     * Create a new rule instance.
     *
     * @param int $fmtEx
     */
    public function __construct(int $fmtEx)
    {
        $this->fmtEx = $fmtEx;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        Carbon::
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
