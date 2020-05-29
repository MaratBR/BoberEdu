<?php

namespace App\Http\Requests\Teachers;

use App\Http\Requests\AdminRequest;
use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\IPayloadRequest;
use App\Utils\Convert;

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
            'passNum' => 'string'
        ];
    }

    function getPayload(): array
    {
        $d = $this->validated();
        return [
            'passport_num' => $d['passNum'],
            'about' => $d['about'],
            'full_name' => $d['fullName'],
        ];
    }
}
