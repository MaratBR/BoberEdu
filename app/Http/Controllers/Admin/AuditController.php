<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\DTO\Admin\AuditDto;
use App\Http\DTO\PaginationDto;
use App\Services\Abs\IAuditService;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    private $repo;

    public function __construct(IAuditService $auditService)
    {
        $this->repo = $auditService;
    }

    public function paginate()
    {
        return new PaginationDto($this->repo->all(), AuditDto::class);
    }
}
