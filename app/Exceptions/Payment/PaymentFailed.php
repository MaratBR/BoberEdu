<?php

namespace App\Exceptions\Payment;

use App\Exceptions\ServiceException;
use Exception;

class PaymentFailed extends ServiceException
{
    public function __construct(string $message = null, \Throwable $previous = null, array $headers = [], ?int $code = 0)
    {
        parent::__construct(400, $message, $previous, $headers, $code);
    }
}
