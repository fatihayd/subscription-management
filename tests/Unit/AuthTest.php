<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use WithFaker;

    public function test_new_users_can_register(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => $this->faker->name,
            'email' => $email = $this->faker->unique()->safeEmail,
            'password' => $this->faker->password(),
        ]);

        User::where('email', $email)->delete();
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'name',
            'email',
            'updated_at',
            'created_at',
            'id',
            'access_token'
        ]);
    }

    public function test_user_cant_register_without_name(): void
    {
        $response = $this->postJson('/api/register', [
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password(),
        ]);

        $response->assertStatus(422);
    }

    public function test_user_cant_register_without_email(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => $this->faker->name,
            'password' => $this->faker->password(),
        ]);

        $response->assertStatus(422);
    }

    public function test_user_cant_register_with_invalid_email(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => $this->faker->name,
            'email' => str_replace('@', '', $this->faker->safeEmail),
            'password' => $this->faker->password(),
        ]);

        $response->assertStatus(422);
    }

    public function test_user_cant_register_without_password(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
        ]);

        $response->assertStatus(422);
    }
}
