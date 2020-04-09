<?php


namespace App\Services\Abs;


use App\Purchase;
use App\User;

interface IPurchasesService
{
    function get(int $id): Purchase;

    function create(string $title, string $redirect, float $price, User $customer): Purchase;
}
