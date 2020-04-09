<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\AuthenticatedRequest;
use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends AuthenticatedRequest
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
            'status' => 'string',
            'sex' => 'in:u,m,f'
        ];
    }
}
