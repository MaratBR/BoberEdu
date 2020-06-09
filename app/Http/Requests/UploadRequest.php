<?php

namespace App\Http\Requests;

class UploadRequest extends AuthenticatedRequest
{
    public function openBody()
    {
        return fopen('php://input', 'r');
    }

    public function validateBodySize()
    {
    }
}
