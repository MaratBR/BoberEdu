<?php


namespace App\Http\Requests;


use App\Exceptions\ThrowUtils;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Utils
{
    use ThrowUtils;

    private static $trueStrings = [
        '1', 'yes', 'y', 'true', 't', '+'
    ];

    public static function asBool($val)
    {
        return gettype($val) === 'string' && in_array(strtolower($val), self::$trueStrings);
    }

    public static function asInt($val, ?int $default = null): int
    {
        $result = $default;
        if (is_string($val)) {
            $intv = ctype_digit($val) ? intval($val) : null;
            if ($intv !== null)
                return $intv;
        }

        if ($result === null)
            throw new HttpException(400, "Invalid integer: $val");
        return $result;
    }
}
