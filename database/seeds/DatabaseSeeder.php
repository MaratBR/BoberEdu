<?php

use Illuminate\Database\Seeder;

require  __DIR__ . '/UsersTableSeeder.php';
require  __DIR__ . '/CoursesTableSeeder.php';
require  __DIR__ . '/UnitsTableSeeder.php';
require  __DIR__ . '/LessonsTableSeeder.php';

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CoursesTableSeeder::class);
        $this->call(UnitsTableSeeder::class);
        $this->call(LessonsTableSeeder::class);
    }
}
