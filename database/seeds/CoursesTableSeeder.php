<?php


class CoursesTableSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        for ($i = 0; $i < 150; $i++) {
            \App\Course::create([
                'name' => 'Test' . $i,
                'about' => 'Just a test course, nothing much',
                'price' => 1299.99,
                'available' => true
            ]);
        }
    }
}
