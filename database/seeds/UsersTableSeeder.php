<?php


use App\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder
{
    public function __call()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@noreply.com',
            'password' => Hash::make('AdminAdmin')
        ])->save();
    }
}
