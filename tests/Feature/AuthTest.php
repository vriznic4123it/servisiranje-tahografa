<?php

use App\Models\User;

test('login page loads successfully', function () {
    $response = $this->get('/login');
    $response->assertStatus(200);
});

test('user can login with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);
    
    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);
    
    $response->assertRedirect('/dashboard');
});

test('authenticated user can logout', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
        ->post('/logout');
    
    $response->assertRedirect('/');
});