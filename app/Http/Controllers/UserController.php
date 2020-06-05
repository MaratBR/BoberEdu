<?php

namespace App\Http\Controllers;

use App\Exceptions\ThrowUtils;
use App\Http\DTO\PaginationDto;
use App\Http\DTO\Uploads\UploadedDto;
use App\Http\DTO\Users\UserDto;
use App\Http\DTO\UserProfileDto;
use App\Http\DTO\UserSettingsDto;
use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\Users\EditUserRequest;
use App\Http\Requests\Users\SetStatusRequest;
use App\Services\Abs\IEnrollmentService;
use App\Services\Abs\IUploadService;
use App\Services\Abs\IUsersService;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ThrowUtils;

    private $users;

    public function __construct(IUsersService $service)
    {
        $this->users = $service;
    }

    /**
     *  Returns user with roles
     *
     * @param AuthenticatedRequest $request
     * @param int $id
     * @return UserDto
     */
    public function get(Request $request, int $id)
    {
        $user = $this->users->get($id);

        return new UserDto($user);
    }

    /**
     * Returns list (pagination) of users
     *
     * @param AuthenticatedRequest $request
     * @return mixed
     */
    public function index(AuthenticatedRequest $request)
    {
        $this->throwForbiddenIfNotAllowed('paginate', User::class);
        $paginator = $this->users->paginate();
        return new PaginationDto($paginator, UserDto::class);
    }

    public function profile(int $userId, IEnrollmentService $enrollment)
    {
        $user = $this->users->get($userId);
        $enrollments = $enrollment->getUserEnrollsWithCourses($user);

        return new UserProfileDto($user, $enrollments);
    }

    public function setStatus(SetStatusRequest $request)
    {
        $user = $request->user();

        $user->update([
            'status' => $request->getStatus()
        ]);

        return $this->noContent();
    }

    public function settings(AuthenticatedRequest $request) {
        $user = $request->user();
        return new UserSettingsDto($user);
    }

    public function checkUsername(string $username) {
        return response()->json($this->users->userNameTaken($username));
    }

    public function uploadAvatar(AuthenticatedRequest $request, IUploadService $uploads) {
        $user = $request->user();
        $file = fopen('php://input', 'r');
        if (!$file)
            return response()->json(['message' => 'failed to open stream'], 500);

        $fileInfo = $uploads->uploadAvatar($user, $file);

        return new UploadedDto($fileInfo);
    }

    public function updateProfile(EditUserRequest $request) {
        $request->user()->update($request->getPayload());
        return $this->done();
    }
}
