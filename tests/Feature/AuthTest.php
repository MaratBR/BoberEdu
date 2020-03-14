<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use WithFaker;

    /**
     * Tries to create new user.
     *
     * @return void
     */
    public function testRegister()
    {
        $this->createUser();
    }

    public function testLogin()
    {
        $user = $this->createUser();
        $pwd = $user['pwd'];
        $user = $user['user'];
        $loginData = $user['login'];

        $resp = $this->post('/api/auth/login', [
            'name' => $user->name,
            'password' => $pwd
        ]);
        $resp->assertOk();

        $loginData2 = $resp->json();

        $resp = $this->get('/api/auth/user', ['Authorization' => "{$loginData['tokenType']} {$loginData['accessToken']}"]);
        $resp2 = $this->get('/api/auth/user', ['Authorization' => "{$loginData2['tokenType']} {$loginData2['accessToken']}"]);

        $resp->assertOk();
        $resp2->assertOk();

        self::assertEquals($resp->json('id'), $resp2->json('id'));
    }

    public function createUser()
    {
        $pwd = $this->faker->password;
        $name = $this->faker()->userName;
        $email = $this->faker()->email;


        $resp = $this->post('/api/auth/register', [
            'name' => $name,
            'password' => $pwd,
            'email' => $email
        ]);

        $resp->assertCreated();

        return [
            'user' => User::findOrFail($resp->json('user.id')),
            'pwd' => $pwd,
            'login' => $resp->json('login')
        ];
    }
}
