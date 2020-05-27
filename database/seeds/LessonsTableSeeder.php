<?php


use App\Lesson;
use Illuminate\Database\Seeder;

class LessonsTableSeeder extends Seeder
{
    public function run()
    {
        Lesson::create([
            'title' => 'Lesson #1',
            'content' => 'This is really interesting but no',
            'unit_id' => 1,
            'order_num' => 0,
            'summary' => 'Test. test'
        ]);
    }
}
