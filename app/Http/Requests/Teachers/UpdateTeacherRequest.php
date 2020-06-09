<?php

namespace App\Http\Requests\Teachers;

use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\IPayloadRequest;

class UpdateTeacherRequest extends AuthenticatedRequest implements IPayloadRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fullName' => 'string',
            'about' => 'string',
        ];
    }

    function getPayload(): array
    {
        $d = $this->validated();
        return [
            'about' => $d['about'],
            'full_name' => $d['fullName'],
        ];
    }
}
