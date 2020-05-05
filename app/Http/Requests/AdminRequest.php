<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class AdminRequest extends AuthenticatedRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return parent::authorize() && Auth::user()->isAdmin();
    }
}
