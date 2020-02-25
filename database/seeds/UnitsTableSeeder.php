<?php


class UnitsTableSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        \App\Unit::create([
            'name' => 'Unit #1',
            'is_preview' => true,
            'course_id' => 1,
            'about' => 'In this unit you will learn nothing because it\'s just an example'
        ]);
    }
}
