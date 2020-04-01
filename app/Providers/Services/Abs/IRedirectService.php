<?php


namespace App\Providers\Services\Abs;


use App\UserCourse;

interface IRedirectService
{
    function paymentRedirect(UserCourse $record): string;
}
