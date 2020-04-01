<?php


namespace App\Providers\Services\Abs;


use App\CourseAttendance;
use App\Purchase;
use App\User;

interface IPurchasesService
{
    function get(int $id): Purchase;

    function create(string $title, string $redirect, float $price, User $customer): Purchase;
}
