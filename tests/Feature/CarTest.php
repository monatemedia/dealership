<?php

// Test for accessing the car create page as an unauthenticated user
it('should not be possible to access car create page as guest user', function () {
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->get(route('car.create'));

    // Assert that the response is a redirect to the login route
    $response->assertRedirectToRoute('login');
    // Assert that the response status is 302 (redirect)
    $response->assertStatus(302);
});

// Test for accessing the car create page as an authenticated user
it('should be possible to access car create page as authenticated user', function () {
    /** @var \Illuminate\Testing\TestResponse $response */

    // Create a user and authenticate
    $user = \App\Models\User::factory()->create();
    // Act as the authenticated user
    $response = $this->actingAs($user)
        // Make a GET request to the car create route
        ->get(route('car.create'));

    $response->assertOK()
        ->assertSee('Add new car');
});
