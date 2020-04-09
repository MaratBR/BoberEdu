<?php

namespace App\Services\Abs;

interface IExternalPayment
{
    function id(): string;

    function redirect(): string;
}
