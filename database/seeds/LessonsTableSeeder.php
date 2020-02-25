<?php


class LessonsTableSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        \App\Lesson::create([
            'title' => 'Lesson #1',
            'content' => 'This is really interesting but no',
            'unit_id' => 1,
            'order_num' => 0
        ]);
    }
}
