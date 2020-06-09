<?php


namespace App\Services\Implementation;


use App\AuditRecord;
use App\Services\Abs\IAuditService;
use App\User;
use App\Utils\Audit\Audit;
use Illuminate\Pagination\LengthAwarePaginator;

class AuditService implements IAuditService
{

    function all(): LengthAwarePaginator
    {
        /** @var LengthAwarePaginator $paginator */
        $paginator = AuditRecord::query()->orderBy('created_at', 'desc')->paginate(100);
        return $paginator;
    }

    function byUser(User $user): LengthAwarePaginator
    {
        /** @var LengthAwarePaginator $paginator */
        $paginator = AuditRecord::query()->where('user_id', '=', $user->id)->paginate();
        return $paginator;
    }

    function forSubject($subject): LengthAwarePaginator
    {
        /** @var LengthAwarePaginator $paginator */
        $paginator = AuditRecord::query()
            ->where('subject', '=', Audit::subjectId($subject))
            ->where('subject_type', '=', Audit::subjectType($subject))
            ->paginate();
        return $paginator;
    }
}
