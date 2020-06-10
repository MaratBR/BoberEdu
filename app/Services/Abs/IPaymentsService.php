<?php


namespace App\Services\Abs;


use App\Course;
use App\Payment;
use App\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

interface IPaymentsService
{
    function createPayment(Course $course, Request $request, User $user, string $gatewayName, array $data): Payment;

    function hasGateaway(string $gateaway);

    function externalPaymentStatus(Payment $payment): ?bool;
}
