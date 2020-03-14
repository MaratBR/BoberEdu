<?php


namespace App\Providers\Services;


use Ramsey\Uuid\Uuid;

class FakePaymentsServiceExternal implements IExternalPaymentService
{

    function placePayment(float $price, string $title, string $redirectUrl): string
    {
        return Uuid::uuid4()->getHex();
    }

    function checkPayment(string $id): string
    {
        return 'successful';
    }
}
