<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string name
 * @property string description
 * @method static Role create(array $array)
 */
class Role extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'description'];

    public static function getOrNull(string $roleName): Role
    {
        /** @var Role $role */
        $role = Role::query()->where('name', $roleName)->first();

        return $role;
    }

    public static function newRole(string $name, string $description): Role
    {
        return self::create([
            'name' => $name,
            'description' => $description
        ]);
    }

    public static function ensure(string $roleName): Role
    {
        $role = self::getOrNull($roleName);

        if (!$role)
            $role = self::newRole($roleName, "Brand new role");

        return $role;
    }

    public static function createAll(array $roles)
    {
        $data = [];

        foreach ($roles as $name => $description)
        {
            $data[] = [
                'name' => $name,
                'description' => $description
            ];
        }

        self::insert($data);
    }
}
