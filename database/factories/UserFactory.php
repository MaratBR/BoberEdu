<?php

/** @var Factory $factory */

use App\Role;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Hash;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->userName,
        'password' => Hash::make('password'),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now()
    ];
});

$factory->afterCreatingState(User::class, 'admin', function (User $user, Faker $faker) {
    $user->roles()->save(Role::ensure('admin'));
});
