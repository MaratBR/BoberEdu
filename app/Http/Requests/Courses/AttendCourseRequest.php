<?php

namespace App\Http\Requests\Courses;

use App\Http\Requests\AuthenticatedRequest;
use App\Providers\Services\Abs\ICourseAttendanceInfo;

class AttendCourseRequest extends AuthenticatedRequest implements ICourseAttendanceInfo
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

    function isPreview(): bool
    {
        return $this->validated()['preview'] ?? ICourseAttendanceInfo::DEFAULT_IS_PREVIEW;
    }

    function giftTo(): ?int
    {
        return $this->validated()['gift_to'] ?? null;
    }

    function getCourseId(): int
    {
        return $this->validated()['course_id'];
    }
}
