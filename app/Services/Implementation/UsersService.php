<?php


namespace App\Services\Implementation;


use App\Exceptions\ThrowUtils;
use App\Services\Abs\IUsersService;
use App\User;
use Illuminate\Support\Facades\Hash;

class UsersService implements IUsersService
{
    use ThrowUtils;


    public function get(int $id): User
    {
        return User::findOrFail($id);
    }

    function getWithRoles(int $id): User
    {
        // It's fine, IDEA, calm down
        /** @var User $user */
        $user = User::with('roles')->findOrFail($id);
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

    function normalize(string $username)
    {
        return strtoupper($username);
    }

}
