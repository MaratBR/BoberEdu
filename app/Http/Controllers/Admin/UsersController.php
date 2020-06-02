<?php

namespace App\Http\Controllers\Admin;

use App\AuditRecord;
use App\Http\Controllers\Controller;
use App\Http\DTO\PaginationDto;
use App\Http\DTO\Users\AdminUserDto;
use App\Http\DTO\Users\UserDto;
use App\Http\DTO\Utils\ItemsDto;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\Users\EditUserRequest;
use App\Http\Requests\Users\PromoteUserRequest;
use App\Services\Abs\IUploadService;
use App\Services\Abs\IUsersService;
use App\Utils\Audit\Audit;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    private $repo;

    public function __construct(IUsersService $users)
    {
        $this->repo = $users;
    }

    public function get(int $id)
    {
        return new AdminUserDto($this->repo->get($id));
    }

    public function update(EditUserRequest $userRequest, int $id)
    {
        $user = $this->repo->get($id);
        $user->update(
            $userRequest->getPayload()
        );

        AuditRecord::make($userRequest->user(), $userRequest, Audit::UPDATE)->subject($user)->build();

        return new AdminUserDto($user);
    }

    public function create(RegisterRequest $request)
    {
        $user = $this->repo->create($request->getPayload());

        return new AdminUserDto($user);
    }

    public function uploadAvatar(EditUserRequest $userRequest, int $id, IUploadService $uploadService)
    {
        $user = $this->repo->get($id);
        $avatar = $uploadService->uploadAvatar($userRequest->user(), $this->openInput());

        $this->repo->setAvatar($user, $avatar);
        AuditRecord::make($userRequest->user(), $userRequest, Audit::UPLOAD_AVATAR)->subject($user)->build();

        return [
            'id' => $avatar->sys_name
        ];
    }

    public function paginate(Request $request)
    {
        $order = $request->query('order');
        if (is_array($order))
            $order = $order[0];
        if ($order !== null) {
            if (!in_array($order, ['id', 'age', 'email']))
                $order = 'name';
        }

        return new PaginationDto($this->repo->paginate(50, $order), AdminUserDto::class);
    }

    public function promote(PromoteUserRequest $request, int $userId)
    {
        $user = $this->repo->get($userId);

        if ($user->is_admin != $request->isAdmin())
        {
            AuditRecord::make($request->user(), $request, Audit::PROMOTE)
                ->subject($user)->comment($request->getComment())->build();

            $user->update([
                'is_admin' => $request->isAdmin()
            ]);
        }

        return $this->noContent();
    }

    private const USER_SEARCH_PARAMS = ['id', 'email', 'name'];
    public function search(SearchRequest $request)
    {
        if ($request->getQuery() === null)
            return $this->paginate($request);


        if ($request->hasParametricSearch() && in_array($request->getParameter(), self::USER_SEARCH_PARAMS)) {
            $val = $request->getQuery();
            $results = [$this->repo->getBy($request->getParameter(), $val)];
        } else {
            $results = $this->repo->search($request->getQuery());
        }

        return new PaginationDto($results, AdminUserDto::class);
    }
}
