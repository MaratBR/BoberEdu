<?php

namespace App\Providers\Services\Abs;

interface IExternalPayment
{
    function id(): string;
    function redirect(): string;
}
