<?php

namespace App\Http\Requests;

class DeleteRequest extends AuthenticatedRequest
{
    public function isForce(): bool
    {
        return Utils::asBool($this->query('force'));
    }
}
