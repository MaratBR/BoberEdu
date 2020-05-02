<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\IPayloadRequest;
use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends AuthenticatedRequest implements IPayloadRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string',
            'about' => 'string',
            'status' => 'string',
            'sex' => 'in:u,m,f'
        ];
    }

    function getPayload(): array
    {
        return $this->validated();
    }
}
