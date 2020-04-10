<?php


namespace App\Services\Abs;


use App\Purchase;
use App\User;

interface IPurchasesService
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';

    function get(int $id): Purchase;

    function getForUser(int $id, User $user): Purchase;

    function create(string $title, string $redirect, float $price, User $customer): Purchase;
}
