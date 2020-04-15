<?php


namespace App\Services\Abs\Payments;


use Omnipay\Common\Message\ResponseInterface;

interface IPaymentGatewayHandler
{
    function request(string $paymentTitle, float $amount, string $localPaymentId, array $data): ResponseInterface;
}
