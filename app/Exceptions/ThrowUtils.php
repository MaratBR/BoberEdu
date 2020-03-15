<?php


namespace App\Exceptions;


use Illuminate\Support\Facades\Gate;
use Lanin\Laravel\ApiExceptions\ForbiddenApiException;
use Lanin\Laravel\ApiExceptions\NotFoundApiException;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait ThrowUtils
{
    public function throwErrorIf(int $code, string $message, bool $condition)
    {
        if ($condition)
            throw new HttpException($code, $message);
    }

    public function throwForbidden(string $message, bool $condition)
    {
        $this->throwErrorIf(403, $message, $condition);
    }

    public function throwForbiddenIfNotAllowed($ability, $argument, string $msg)
    {
        $allows = gettype($ability) === 'array' ? Gate::any($ability, $argument) : Gate::allows($ability, $argument);
        $this->throwForbidden($msg, !$allows);
    }

    public function throwNotFoundIfNull($val, ?string $msg = null)
    {
        $this->throwErrorIf(
            404,
            $msg ?? "Record not found",
            $val === null);
        return $val;
    }
}
