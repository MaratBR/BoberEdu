<?php

namespace App\Http\Requests\Payments;

use App\Course;
use App\Http\Requests\AuthenticatedRequest;

class InitPaymentRequest extends AuthenticatedRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'course_id' => 'numeric|required',
            'preview' => 'boolean',
            'gift_to' => 'numeric'
        ];
    }

    public function getCourseId(): int
    {
        return $this->validated()['course_id'];
    }

    public function isPreview(): bool
    {
        return $this->validated()['preview'];
    }

    public function getRecipientId(): ?int
    {
        return $this->validated()['gift_to'] ?? null;
    }

}
