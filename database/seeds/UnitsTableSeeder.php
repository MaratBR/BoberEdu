<?php


use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitsTableSeeder extends Seeder
{
    public function run()
    {
        Unit::create([
            'name' => 'Unit #1',
            'preview' => true,
            'course_id' => 1,
            'about' => 'In this unit you will learn nothing because it\'s just an example'
        ]);
    }
}
