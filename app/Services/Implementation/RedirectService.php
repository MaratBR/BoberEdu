<?php


namespace App\Services\Implementation;


use App\Services\Abs\IRedirectService;
use App\UserCourse;

class RedirectService implements IRedirectService
{

    function paymentRedirect(UserCourse $record): string
    {
        return "TODO " . $record->course_id;
    }
}
