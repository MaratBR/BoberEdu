<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CourseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateCourse()
    {
        $id  = $this->createCourse('1');
        $response= $this->get("/api/courses/$id");
        $response->assertStatus(200);
        self::assertEquals($id, $response->json('id'));
    }

    public function createCourse(string $prefix): int {
        $response = $this->post('/api/courses', [
            'about' => $prefix. 'Test course about',
            'name' => $prefix . 'Test',
            'price' => 120.34,
            'available' => true
        ])->assertStatus(201);
        self::assertIsInt($response->json('id'));
        return $response->json('id');
    }

    public function testDeleteCourse() {
        $id = $this->createCourse('2');
        $response = $this->delete("/api/courses/$id");
        $response->assertNoContent();
        $this->get('/api/courses/' . $id)->assertNotFound();
    }

    public function testUpdateCourse() {
        $id = $this->createCourse('3');
        $response = $this->put('/api/courses/' . $id, [
            'about' => '123456789',
            'price' => 12345678
        ]);
        $response->assertNoContent();
        $course = $this->get('/api/courses/' . $id);
        $course->assertOk();
        self::assertEquals('123456789', $course->json('about'));
        self::assertEquals(12345678, $course->json('price'));
    }

    public function testInvalidCourseValues() {
        $this->post('/api/courses', [
            'about' =>'Test course about',
            'name' => 'This will fail',
            'price' => 99999999999999999999,
            'available' => true
        ])->assertStatus(422);

        $this->post('/api/courses', [
            'about' =>'Test course about',
            'name' => 'This will fail',
            'price' => 213,
            'available' => 'not boolean'
        ])->assertStatus(422);

        $this->post('/api/courses', [
            'about' =>'Test course about',
            'name' => str_repeat('1', 256), // too long
            'price' => 12,
            'available' => true
        ])->assertStatus(422);
    }
}
