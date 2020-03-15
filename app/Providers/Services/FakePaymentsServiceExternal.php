<?php


namespace App\Providers\Services;


use App\Providers\Services\Abs\IExternalPayment;
use App\Providers\Services\Abs\IExternalPaymentService;
use Ramsey\Uuid\Uuid;

class FakeExternalPayment implements IExternalPayment
{
    private $id;
    private $redirectURL;

    public function __construct(string $id, string $redirectURL)
    {
        $this->id = $id;
        $this->redirectURL = $redirectURL;
    }

    function id(): string
    {
        return $this->id;
    }

    function redirect(): string
    {
        return $this->redirectURL;
    }
}

class FakePaymentsServiceExternal implements IExternalPaymentService
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
