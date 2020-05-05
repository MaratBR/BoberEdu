<?php

namespace Tests\Feature;

use App\User;
use Tests\TestWithAuthentication;

class AdminTest extends TestWithAuthentication
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAdminRole()
    {
        /** @var User $user */
        $user = factory(User::class)->state('admin')->create();

        $token = $this->authenticate($user->name, 'password');
        $roles = $this->get('/api/auth/user', [
            'Authorization' => "Bearer $token"
        ])->json('roles');
        $this->assertContains('admin', $roles);
    }
}
