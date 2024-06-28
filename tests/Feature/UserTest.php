<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

    public function test_user_can_register_success()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test_user@example.com',
            'password' => 'test@147852369',
            'password_confirm' => 'test@147852369',
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'data' => [
                'name' => 'Test User',
                'email' => 'test_user@example.com',
            ],
        ]);
    }

    public function test_registration_errors()
    {
        $response = $this->postJson('/api/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'password',
            'password_confirm' => 'different-password',
        ]);
        $response->assertStatus(401);
        // $response->assertJsonValidationErrors(['message.name', 'message.email', 'message.password_confirm']);
        $response->assertJsonValidationErrors(['name', 'email', 'password_confirm']);
    }

    public function test_user_cannot_register_with_exists_email()
    {
        User::create([
            'name' => 'User',
            'email' => 'test_user@example.com',
            'password' => 'password',
            'password_confirm' => 'password',
        ]);

        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test_user@example.com',
            'password' => 'password',
            'password_confirm' => 'password',
        ]);

        $response->assertStatus(401);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_user_can_login_success()
    {

        $user = User::create([
            'name' => 'Test Login',
            'email' => 'test_login@example.com',
            'password' => Hash::make('test@147852369'),
        ]);
        $response = $this->postJson('/api/login', [
            'email' => 'test_login@example.com',
            'password' => 'test@147852369',
        ]);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'token',
            'data' => [
                'id',
                'name',
                'email',
                'role',
            ],
        ]);
    }

    public function test_user_login_error_with_incorrect(){
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user-test@example.com',
            'password' => Hash::make('test@147852369'),
        ]);
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'test@147852369sss',
        ]);
        $response->assertStatus(401);

        // Assert that the user is not authenticated
        $this->assertGuest();
    }
}
