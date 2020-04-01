<?php


namespace App\Providers\Services;


use App\Providers\Services\Abs\IExternalPayment;
use App\Providers\Services\Abs\IExternalPaymentService;
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

    function checkPayment(string $id): string
    {
        return 'successful';
    }
}
