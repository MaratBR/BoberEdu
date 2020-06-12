<?php


namespace App\Services\Abs;


use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface IAuditService
{
    function all(): LengthAwarePaginator;

    function byUser(User $user): LengthAwarePaginator;

    function forSubject($subject): LengthAwarePaginator;
}
