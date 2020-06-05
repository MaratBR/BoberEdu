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
            'normalized_name' => 'ADMIN',
            'normalized_email' => 'ADMIN@NOREPLY.COM',
            'email' => 'admin@noreply.com',
            'password' => Hash::make('AdminAdmin'),
            'is_admin' => true
        ]);
    }
}
