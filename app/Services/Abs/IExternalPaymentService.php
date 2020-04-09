<?php


namespace App\Services\Abs;

interface IExternalPaymentService
{
    function placePayment(float $price, string $title, string $redirectUrl): IExternalPayment;
}
