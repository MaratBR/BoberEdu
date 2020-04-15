<?php


namespace App\Services\Implementation\Payments;


use App\Services\Abs\Payments\IPaymentGatewayHandler;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Omnipay;

class DummyGatewayHandler implements IPaymentGatewayHandler
{

    function request(string $paymentTitle, float $amount, array $data): ResponseInterface
    {
        $gateway = Omnipay::create('Dummy');

        $validator = Validator::make($data, [
            'card.number' => 'required|string',
            'card.expiryMonth' => 'required|string',
            'card.expiryYear' => 'required|string'
        ]);
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $data['amount'] = $amount;

        return $gateway->purchase($data)->send();
    }
}
