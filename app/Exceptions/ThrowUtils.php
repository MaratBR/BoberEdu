<?php


namespace App\Exceptions;


use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait ThrowUtils
{
    public function throwForbiddenIfNotAllowed($ability, $argument, string $msg = null)
    {
        $msg = $msg ?: (Request::method() == 'get' ? 'You can\'t access this resource' : 'You are not allowed to do this action');
        $allows = gettype($ability) === 'array' ? Gate::any($ability, $argument) : Gate::allows($ability, $argument);
        $this->throwForbidden($msg, !$allows);
    }

    public function throwForbidden(string $message, bool $condition)
    {
        $this->throwErrorIf(403, $message, $condition);
    }

    public function throwErrorIf(int $code, string $message, bool $condition)
    {
        if ($condition)
            throw new HttpException($code, $message);
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
