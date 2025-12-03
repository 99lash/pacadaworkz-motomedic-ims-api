<?php

use App\Models\User;

// Wag niyo muna pansinin 'to heheh, pinapractice ko. :) -@99lash

test('authenticated user cannot access login endpoint', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'api')
        ->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

    $response->assertStatus(403)
        ->assertJson([
            'success' => false,
            'message' => 'User is already logged in.',
        ]);
});

test('authenticated user cannot access google login endpoint', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'api')
        ->postJson('/api/v1/auth/login/google', [
            'token' => 'some-token',
        ]);

    $response->assertStatus(403)
        ->assertJson([
            'success' => false,
            'message' => 'User is already logged in.',
        ]);
});

test('guest user can access login endpoint', function () {
    // We expect 422 (validation error) or 401 (invalid credentials) or 200,
    // just not 403 Forbidden from our middleware.
    // Since we aren't providing valid credentials, 422 or 401 is expected.
    // However, checking that it is NOT 403 is sufficient to prove the middleware allows guests.

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'test@example.com',
        'password' => 'wrongpassword',
    ]);

    // The controller will likely return 401 for bad creds, or validation error.
    // It should definitely NOT be 403.
    $response->assertStatus(401);
});
