<?php

namespace App\Services\Implementation;

use App\Services\Abs\IExternalPayment;

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
