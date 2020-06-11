<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'Development',
            'Health',
            'Design',
            'Life',
            'Science'
        ];

        foreach ($names as $name) {
            Category::create([
                'name' => $name,
                'about' => 'Yet another category'
            ]);
        }
    }
}
