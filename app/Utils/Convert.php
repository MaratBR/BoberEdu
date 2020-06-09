<?php


namespace App\Utils;


use Illuminate\Support\Str;

class Convert
{
    public static function toSnakeCase($value)
    {
        if (is_string($value))
            return Str::snake($value);
        elseif (is_array($value)) {
            $newValue = [];

            foreach ($value as $key => $v) {
                $newValue[self::toSnakeCase($key)] = is_array($v) ? self::toSnakeCase($v) : $v;
            }

            return $newValue;
        } else {
            return $value;
        }
    }

    public static function onlyKeys(array $value, array $keys): array
    {
        return array_filter($value, function ($key) use ($keys) {
            return in_array($key, $keys);
        }, ARRAY_FILTER_USE_KEY);
    }

    public static function escapeElasticReservedChars($query)
    {
        return preg_replace(
            '/[\\+\\-\\=\\&\\|\\!\\(\\)\\{\\}\\[\\]\\^\\\"\\~\\*\\<\\>\\?\\:\\\\\\/]/',
            addslashes('\\$0'),
            $query
        );
    }
}
