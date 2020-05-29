<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AuthenticatedRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() != null;
    }

    public function rules()
    {
        return [];
    }

    public function user($guard = null): User
    {
        return parent::user($guard); // TODO: Change the autogenerated stub
    }
}
