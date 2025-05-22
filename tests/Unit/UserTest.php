<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function attributes_are_fillable()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $user = new User($userData);

        $this->assertEquals($userData['name'], $user->name);
        $this->assertEquals($userData['email'], $user->email);
        // Password will be hashed, so we just check it's not null here.
        // The actual hashing is tested in another test.
        // Or, if User model doesn't hash on `new User()`, we'd compare directly.
        // However, typical Laravel User model hashes on setPasswordAttribute or saving.
        // For a `new User()` call, the password might be stored plain until save,
        // or hashed immediately if setPasswordAttribute is aggressive.
        // We'll check that it's not the plain password if the mutator is active,
        // or that it's been set if the mutator isn't active until save.
        // The main thing is that 'password' can be passed in.
        $this->assertNotNull($user->password);
        // If setPasswordAttribute is active on new User(), this will also be true:
        // $this->assertNotEquals($userData['password'], $user->password);
        // However, a more direct test for hashing is done in password_is_hashed_on_creation
    }

    /** @test */
    public function password_is_hashed_on_creation()
    {
        $plainPassword = 'password123';
        
        // Using User::create() to directly test the model's behavior
        // including the setPasswordAttribute during mass assignment and save.
        $user = User::create([
            'name' => 'Hashing Test User',
            'email' => 'hash@example.com', // Ensure email is unique for each test run if not using RefreshDatabase effectively
            'password' => $plainPassword,
        ]);

        $this->assertNotEquals($plainPassword, $user->password);
        $this->assertTrue(Hash::check($plainPassword, $user->password));
    }

    /** @test */
    public function password_and_remember_token_are_hidden()
    {
        $user = User::factory()->create();
        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('password', $userArray);
        $this->assertArrayNotHasKey('remember_token', $userArray);
    }
}
