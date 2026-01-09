<?php

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('create new user action creates a user', function () {
    $action = new CreateNewUser;
    $data = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $user = $action->create($data);

    expect($user)->toBeInstanceOf(User::class);
    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
});

test('create new user action validates input', function () {
    $action = new CreateNewUser;
    $data = [
        'name' => '', // Required
        'email' => 'invalid-email', // Invalid email
        'password' => 'short', // Too short (usually default is 8)
        'password_confirmation' => 'mismatch',
    ];

    $action->create($data);
})->throws(ValidationException::class);

test('create new user action requires unique email', function () {
    User::factory()->create(['email' => 'existing@example.com']);
    
    $action = new CreateNewUser;
    $data = [
        'name' => 'New User',
        'email' => 'existing@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $action->create($data);
})->throws(ValidationException::class);
