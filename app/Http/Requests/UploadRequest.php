<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends AuthenticatedRequest
{
    public function openBody() {
        return fopen('php://input', 'r');
    }

    public function validateBodySize() {}
}
