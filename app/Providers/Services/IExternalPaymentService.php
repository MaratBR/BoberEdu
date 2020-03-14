<?php


namespace App\Providers\Services;


interface IExternalPaymentService
{
    function placePayment(float $price, string $title, string $redirectUrl): string ;
    function checkPayment(string $id): string;
}
