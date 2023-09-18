<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Morris\Core\Enums\User\Role;
use Morris\Core\Models\User;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;

    protected string $password = "passworD1!";
    public function test_user_registers_success(): void
    {
        $data = [
            "email" => "test@email.pl",
            "password" => $this->password,
            "password_confirmation" => $this->password,
            "role" => Role::Host->value,
            "name" => "nick"
        ];

        $response = $this->postJson("/api/register", $data);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(["data", "errors", "message"]);

        $user = User::all()->first();

        $this->assertEquals($data["email"], $user->email);
        $this->assertEquals("pl", $user->preferred_language);
        $this->assertTrue(Hash::check($data["password"], $user->password));
        $this->assertEquals(Role::Host, $user->role);
    }

    public function test_user_registers_with_default_role(): void
    {
        $data = [
            "email" => "test@email.pl",
            "password" => $this->password,
            "password_confirmation" => $this->password,
            "name" => "nick"
        ];

        $response = $this->postJson("/api/register", $data);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(["data", "errors", "message"]);

        $user = User::all()->first();

        $this->assertEquals($data["email"], $user->email);
        $this->assertEquals("pl", $user->preferred_language);
        $this->assertTrue(Hash::check($data["password"], $user->password));
        $this->assertEquals(Role::Attendee, $user->role);
    }
}

