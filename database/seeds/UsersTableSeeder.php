<?php


use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@noreply.com',
            'password' => Hash::make('AdminAdmin')
        ]);
        $user->addRole('admin');
    }
}
