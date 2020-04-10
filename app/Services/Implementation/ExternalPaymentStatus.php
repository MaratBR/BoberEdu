<?php


namespace App\Services\Implementation;


use App\Services\Abs\IExternalPaymentStatus;

class ExternalPaymentStatus implements IExternalPaymentStatus
{
    private $_success;

    public function __construct(bool $success)
    {
        $this->_success = $success;
    }

    function success(): bool
    {
        return $this->_success;
    }

    function status(): string
    {
        return 'success';
    }
}
