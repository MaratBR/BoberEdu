<?php

namespace App\Http\Controllers\Admin;

use App\AuditRecord;
use App\Http\Controllers\Controller;
use App\Http\DTO\PaginationDto;
use App\Http\DTO\Users\AdminUserDto;
use App\Http\DTO\Users\UserDto;
use App\Http\DTO\Utils\ItemsDto;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Users\EditUserRequest;
use App\Role;
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

        AuditRecord::make($userRequest->user(), $userRequest, Audit::USER_UPDATE)->subject($user)->build();

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

    public function roles()
    {
        return new ItemsDto($this->repo->getRoles(), function (Role $role) {
            return $role->name;
        });
    }
}
