<?php


namespace App\Http\DTO;


use App\Payment;

class PaymentDto extends DtoBase
{
    private $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function isSuccess(): bool
    {
        return $this->payment->is_successful;
    }

    public function getRedirect(): ?string
    {
        return $this->payment->redirect_url;
    }

    public function getTitle(): string
    {
        return $this->payment->title;
    }

    public function getGateaway(): string
    {
        return $this->payment->normalized_gateaway_name;
    }

    public function getTS(): int {
        return  $this->payment->created_at->timestamp;
    }
}
