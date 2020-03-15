<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends AuthenticatedRequest
{
    public function isForce(): bool
    {
        return Utils::asBool($this->query('force'));
    }
}
