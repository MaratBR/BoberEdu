<?php


namespace App\Providers\Services\Implementation;


use App\Providers\Services\Abs\IRedirectService;
use App\UserCourse;

class RedirectService implements IRedirectService
{

    function paymentRedirect(UserCourse $record): string
    {
        return "TODO " . $record->course_id;
    }
}
