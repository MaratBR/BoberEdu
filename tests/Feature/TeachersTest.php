<?php

namespace Tests\Feature;

use App\Course;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestWithAuthentication;

class TeachersTest extends TestWithAuthentication
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNewTeacher()
    {
        $admin = factory(User::class)->state('admin')->create();
        $user = factory(User::class)->create();
        $course = factory(Course::class)->create();
        $token = $this->authenticate($admin->name, 'password');

        $response = $this->post('/api/teachers', [
            'passNum' => $this->faker->numerify('#### ######'),
            'fullName' => "{$this->faker->firstName} {$this->faker->lastName}",
            'userId' => $user->id,
            'comment' => "Testing"
        ], [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertCreated();
        $teacherId = $response->json('id');

        $response = $this->get("/api/teachers/$teacherId");
        $response->assertOk();
    }
}
