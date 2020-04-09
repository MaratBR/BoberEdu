<?php


namespace App\Services\Implementation;

use App\Services\Abs\IExternalPayment;
use App\Services\Abs\IExternalPaymentService;
use Ramsey\Uuid\Uuid;

class FakeExternalPaymentsService implements IExternalPaymentService
{

    function placePayment(float $price, string $title, string $redirectUrl): IExternalPayment
    {
        return new FakeExternalPayment(
            Uuid::uuid4()->getHex(),
            $redirectUrl
        );
    }
}
