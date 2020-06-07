<?php

namespace App\Http\Requests\Teachers;

use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\IPayloadRequest;

class CreateTeacherRequest extends AuthenticatedRequest implements IPayloadRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fullName' => 'required',
            'about' => 'required',
            'userId' => 'numeric|required',
            'comment' => 'required'
        ];
    }

    public function getUserId(): int
    {
        return $this->validated()['userId'];
    }

    function getPayload(): array
    {
        return [
            'full_name' => $this->validated()['fullName'],
            'about' => $this->validated()['about'],
        ];
    }

    function getComment(): string
    {
        return $this->validated()['comment'];
    }
}
