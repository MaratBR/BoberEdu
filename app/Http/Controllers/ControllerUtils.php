<?php


namespace App\Http\Controllers;


use App\Exceptions\ThrowUtils;

trait ControllerUtils
{
    use ThrowUtils;

    protected function created($data)
    {
        return response()->json($data, 201);
    }

    protected function deleteShortcut(?bool $result)
    {
        return $this->noContentOrErrorIfNotSuccess(
            $result,
            500,
            "Failed to delete the record");
    }

    protected function noContentOrErrorIfNotSuccess(bool $success, int $code, string $msg)
    {
        $this->throwErrorIf($code, $msg, !$success);
        return $this->noContent();
    }

    protected function noContent()
    {
        return response()->noContent();
    }

    protected function updateShortcut(?bool $result)
    {
        return $this->noContentOrErrorIfNotSuccess(
            $result,
            500,
            "Failed to update the record"
        );
    }
}
