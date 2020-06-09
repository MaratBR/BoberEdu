<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\AuthenticatedRequest;

class PromoteUserRequest extends AuthenticatedRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reason' => 'required|string|max:300',
            'admin' => 'required|boolean'
        ];
    }

    public function isAdmin(): bool
    {
        return $this->validated()['admin'];
    }

    public function getComment()
    {
        return $this->validated()['reason'];
    }
}
