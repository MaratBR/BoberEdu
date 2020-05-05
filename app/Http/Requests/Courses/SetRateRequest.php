<?php

namespace App\Http\Requests\Courses;

use App\Http\Requests\AuthenticatedRequest;

class SetRateRequest extends AuthenticatedRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'value' => 'integer|required|min:1|max:5'
        ];
    }

    public function getValue(): int
    {
        return $this->validated()['value'];
    }
}
