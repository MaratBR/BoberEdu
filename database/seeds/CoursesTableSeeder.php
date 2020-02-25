<?php


class CoursesTableSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        \App\Course::create([
            'name' => 'Test',
            'about' => 'Just a test course, nothing much',
            'price' => 1299.99,
            'available' => true
        ]);
    }
}
