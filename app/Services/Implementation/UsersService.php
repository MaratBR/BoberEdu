<?php


namespace App\Services\Implementation;


use App\Exceptions\ThrowUtils;
use App\FileInfo;
use App\Role;
use App\Services\Abs\IUsersService;
use App\User;
use App\Utils\Convert;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UsersService implements IUsersService
{
    use ThrowUtils;


    public function get(int $id): User
    {
        return User::findOrFail($id);
    }

    function getBy(string $col, $val): User
    {
        /** @var User $user */
        $user = User::query()->where($col, '=', $val)->firstOrFail();

        return $user;
    }

    function paginate(int $perPage = 15, ?string $order = null)
    {
        $q = User::query();
        if ($order != null)
            $q = $q->orderBy($order);
        return $q->paginate();
    }

    function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['normalized_name'] = $this->normalize($data['name']);
        $data['normalized_email'] = $this->normalize($data['email']);
        $user = User::create($data);
        $user->refresh();
        return $user;
    }

    private function normalize(string $username)
    {
        return strtoupper($username);
    }

    function update(User $user, array $data)
    {
        if (array_key_exists('name', $data)) {
            $data['normalized_name'] = $this->normalize($data['name']);
        }
        if (array_key_exists('email', $data)) {
            $data['normalized_email'] = $this->normalize($data['email']);
        }
        $user->update($data);
    }

    function userNameTaken(string $username): bool
    {
        $username = $this->normalize($username);
        return User::query()->where('normalized_name', '=', $username)->exists();
    }

    function setAvatar(User $user, FileInfo $avatar)
    {
        $user->update([
            'avatar_id' => $avatar->id
        ]);
    }

    function search(string $query): LengthAwarePaginator
    {
        return User::search(Convert::escapeElasticReservedChars($query))->paginate();
    }
}
