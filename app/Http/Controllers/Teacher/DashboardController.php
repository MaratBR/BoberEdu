<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\DTO\TeachersDashboard\TeacherDashboardDto;
use App\Http\Requests\Teachers\TeacherRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function get(TeacherRequest $request)
    {
        $teacher = $request->teacher();

        $courses = $teacher->courses;

        $income = $teacher->courses()
            ->leftJoin('enrollments', 'enrollments.course_id', '=', 'courses.id')
            ->leftJoin('payments', 'payments.id', '=', 'enrollments.payment_id')
            ->where('payments.status', '=', 'successful')
            ->sum('payments.amount');

        return new TeacherDashboardDto($courses, $income);
    }
}
