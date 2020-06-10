<?php


namespace App\Http\DTO;


use App\Payment;
use WhichBrowser\Parser;

class PaymentDto extends DtoBase
{
    private $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function getUid() {
        return $this->payment->uid;
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

    public function getAmount()
    {
        return $this->payment->amount;
    }

    public function getGateaway(): string
    {
        return $this->payment->normalized_gateaway_name;
    }

    public function getTS(): ?int
    {
        return $this->payment->completed_at ? $this->payment->completed_at->timestamp : null;
    }

    public function getUserAgent()
    {
        return (new Parser($this->payment->user_agent))->toString();
    }

    public function getIp()
    {
        return $this->payment->ip_address;
    }
}
