<?php


namespace Tests;


class TestWithAuthentication extends TestCase
{
    function authenticate(string $username, string $password): string
    {
        $resp = $this->post('/api/auth/login', [
            'name' => $username,
            'password' => $password
        ]);

        $resp->assertOk();
        return $resp->json('token');
    }
}
