<?php


namespace App\Services\Implementation;

use App\Services\Abs\IExternalPayment;
use App\Services\Abs\IExternalPaymentService;
use App\Services\Abs\IExternalPaymentStatus;
use Ramsey\Uuid\Uuid;

class FakeExternalPaymentsService implements IExternalPaymentService
{

    function placePayment(float $price, string $title, string $redirectUrl): IExternalPayment
    {
        return new FakeExternalPayment(
            bin2hex(random_bytes(20)),
            $redirectUrl
        );
    }

    function checkPaymentStatus(string $id): IExternalPaymentStatus
    {
        return new ExternalPaymentStatus(true);
    }
}
