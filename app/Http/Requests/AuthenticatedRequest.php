<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AuthenticatedRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::user() != null;
    }

    public function rules()
    {
        return [];
    }
}
