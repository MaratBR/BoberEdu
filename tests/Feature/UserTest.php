<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    private function createUser($uniquePrefix)
    {
        return User::create([
            'name' => $uniquePrefix . '_SOME_USER',
            'password' => Hash::make('123456'),
            'sex' => 'm',
            'email' => $uniquePrefix . '_test@notgmail.notcom'
        ]);
    }

    /**
     *
     * @return void
     */
    public function testUserCreate()
    {
        $username = 'Test_UserName';
        User::create([
            'name' => $username,
            'password' => Hash::make('123456'),
            'sex' => 'm',
            'email' => 'test@notgmail.notcom'
        ])->save();

        $this->assertDatabaseHas('users', [
            'name' => $username
        ]);

        $user = User::query()->where('name', '=', $username)->first();

        $this->assertNotNull($user, 'User cannot be retrieved from database');
        $this->assertEquals($username, $user->name);
        $this->assertEquals('m', $user->sex);
        $this->assertTrue(Hash::check('123456', $user->password));

        User::query()->where('id', '=', $user->id)->delete();

        $this->assertDatabaseMissing('users', [
            'name' => $username
        ]);
    }

    public function testUserAuthApi()
    {
        $q = $this->post('/api/auth/register', [
            'sex' => 'm',
            'name' => 'Test_UserNameApi',
            'password' => '12345678',
            'email' => 'hi@gmail.com'
        ]);
        self::assertEquals(
            201,
            $q->status());

        $user = User::query()->where('name', '=', 'Test_UserNameApi')->first();
        self::assertNotNull($user);

        $q = $this->post('/api/auth/register', [
            'sex' => 'm',
            'name' => 'Test_UserNameApi_NameDoesNotMatterBecauseItWillFailAnyway',
            'password' => '1234',
            'email' => 'hi@gmail.com'
        ]);
        self::assertEquals(422, $q->status());

        $q = $this->post('/api/auth/register', [
            'sex' => 'm',
            'name' => 'Test_UserNameApi',
            'password' => '1234567890',
            'email' => 'hi2@gmail.com'
        ]);
        self::assertEquals(422, $q->status());

        $q = $this->post('/api/auth/login', [
            'name' => $user->name,
            'password' => '12345678'
        ])
            ->assertStatus(200)
            ->assertJsonStructure(['accessToken', 'tokenType', 'expiresIn', 'success']);
        $accessToken = $q->json('accessToken');

        $q = $this->get('/api/auth/user',['Authorization' => "Bearer $accessToken"]);
        $q->assertHeader('Content-Type', 'application/json')
            ->assertStatus(200);
        self::assertEquals($user->name, $q->json('name'));

        User::query()->where('name', '=', 'Test_UserNameApi');
    }

    public function testUserApi() {
        $user = $this->createUser('1');
        $r = $this->get("/api/users/{$user->id}")
            ->assertOk();
        self::assertEquals($user->name, $r->json('name'));

        $users = $this->get('/api/users');
        $users->assertOk();
        self::assertIsArray($users->json());
    }
}
