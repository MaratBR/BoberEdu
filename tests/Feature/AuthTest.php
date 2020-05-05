<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestWithAuthentication;

class AuthTest extends TestWithAuthentication
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

    public function createUser()
    {
        $pwd = $this->faker()->password(8);
        $name = $this->faker()->userName;
        $email = $this->faker()->email;


        $resp = $this->post('/api/auth/register', [
            'name' => $name,
            'password' => $pwd,
            'email' => $email
        ]);
        echo $resp->getContent();
        $resp->assertCreated();

        return [
            'user' => User::findOrFail($resp->json('id')),
            'pwd' => $pwd,
            'login' => $resp->json('login')
        ];
    }

    public function testLogin()
    {
        $user = $this->createUser();
        $pwd = $user['pwd'];
        $user = $user['user'];
        $loginData = $user['login'];

        $token = $this->authenticate($user->name, $pwd);

        $resp = $this->get('/api/auth/user', ['Authorization' => "Bearer {$loginData['token']}"]);
        $resp2 = $this->get('/api/auth/user', ['Authorization' => "Bearer {$token}"]);

        $resp->assertOk();
        $resp2->assertOk();

        self::assertEquals($resp->json('id'), $resp2->json('id'));
    }
}
