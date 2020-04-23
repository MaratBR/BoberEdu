<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Course;
use Faker\Generator as Faker;

$factory->define(Course::class, function (Faker $faker) {
    return [
        'name' => $faker->asciify(),
        'about' => $faker->text,
        'price' => $faker->randomFloat(2, 0, 2000)
    ];
});

$factory->state(Course::class, 'sign_up_period', function (Faker $faker) {
    return [
        'sign_up_beg' => now()->addDays(-$faker->numberBetween(3, 30)),
        'sign_up_end' => now()->addDays($faker->numberBetween(3, 30))
    ];
});
