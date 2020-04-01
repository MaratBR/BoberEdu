<?php


use App\Unit;
use Illuminate\Database\Seeder;

class UnitsTableSeeder extends Seeder
{
    public function run()
    {
        Unit::create([
            'name' => 'Unit #1',
            'is_preview' => true,
            'course_id' => 1,
            'about' => 'In this unit you will learn nothing because it\'s just an example'
        ]);
    }
}
