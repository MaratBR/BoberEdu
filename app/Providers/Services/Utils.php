<?php


namespace App\Providers\Services;


use Illuminate\Support\Facades\Gate;
use Lanin\Laravel\ApiExceptions\ForbiddenApiException;

trait Utils
{
    public function throwError(string $class, string $message, bool $condition)
    {
        if (!$condition)
            throw new $class($message);
    }

    public function throwForbidden(string $message, bool $condition)
    {
        $this->throwError(ForbiddenApiException::class, $message, $condition);
    }

    public function throwForbiddenIfNotAllowed($ability, $argument, string $msg)
    {
        $allows = gettype($ability) === 'array' ? Gate::any($ability, $argument) : Gate::allows($ability, $argument);
        $this->throwForbidden($msg, $allows);
    }
}
