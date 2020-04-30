<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\AuthenticatedRequest;
use Illuminate\Foundation\Http\FormRequest;

class SetStatusRequest extends AuthenticatedRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'nullable|string|max:255'
        ];
    }

    public function getStatus(): ?string
    {
        return $this->validated()['status'];
    }
}
