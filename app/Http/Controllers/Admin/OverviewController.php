<?php

namespace App\Http\Controllers\Admin;

use App\AuditRecord;
use App\Http\Controllers\Controller;
use App\Http\DTO\Admin\AuditDto;
use App\TeacherApprovalForm;

class OverviewController extends Controller
{
    public function get()
    {
        return [
            'teacherApplications' => [
                'awaitingReview' => TeacherApprovalForm::awaitingReview()->count(),
                'rejected' => TeacherApprovalForm::rejected()->count(),
                'approved' => TeacherApprovalForm::approved()->count(),
            ],
            'log' => AuditRecord::log()->get()->mapInto(AuditDto::class)
        ];
    }
}
