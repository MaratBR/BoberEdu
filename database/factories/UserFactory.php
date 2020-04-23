<?php

/** @var Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->userName,
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now()
    ];
});

$factory->afterCreatingState(User::class, 'admin', function (User $user, Faker $faker) {
    $user->roles()->save(\App\Role::ensure('admin'));
});
