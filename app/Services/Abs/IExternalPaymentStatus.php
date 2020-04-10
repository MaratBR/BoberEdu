<?php


namespace App\Services\Abs;


interface IExternalPaymentStatus
{
    function success(): bool;

    function status(): string;
}
