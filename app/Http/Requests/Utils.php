<?php


namespace App\Http\Requests;


use App\User;
use Illuminate\Support\Facades\Auth;

class Utils
{
    private static $trueStrings = [
        '1', 'yes', 'y', 'true', 't', '+'
    ];

    public static function asBool($val)
    {
        return gettype($val) === 'string' && in_array(strtolower($val), self::$trueStrings);
    }
}
