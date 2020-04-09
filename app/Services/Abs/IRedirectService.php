<?php


namespace App\Services\Abs;


use App\UserCourse;

interface IRedirectService
{
    function paymentRedirect(UserCourse $record): string;
}
