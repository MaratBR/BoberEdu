<?php

namespace App\Http\Requests\Teachers;

use App\Http\Requests\AuthenticatedRequest;
use Illuminate\Foundation\Http\FormRequest;

class AssignmentRequest extends AuthenticatedRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reason' => 'string|nullable'
        ];
    }

    public function getReason(): ?string
    {
        return $this->validated()['reason'] ?? null;
    }
}
