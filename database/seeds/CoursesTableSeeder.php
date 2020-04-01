<?php


use App\Course;
use Illuminate\Database\Seeder;

class CoursesTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 0; $i < 150; $i++) {
            Course::create([
                'name' => 'Test' . $i,
                'about' => 'Just a test course, nothing much',
                'price' => 1299.99,
                'available' => true
            ]);
        }
    }
}
