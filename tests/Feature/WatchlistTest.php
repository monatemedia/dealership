<?php

// Test for accessing the My Favourite Cars page as an unauthenticated user
it('should not be possible to access my favourite cars page as guest user', function () {
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->get(route('watchlist.index'));

    // Assert that the response is a redirect to the login route
    $response->assertRedirectToRoute('login');
    // Assert that the response status is 302 (redirect)
    $response->assertStatus(302);
});

// Test for accessing the My Favourite Cars page as an authenticated user
it('should be possible to access my favourite cars page as authenticated user', function () {
    /** @var \Illuminate\Testing\TestResponse $response */

    // Create a user and authenticate
    $user = \App\Models\User::factory()->create();
    // Act as the authenticated user
    $response = $this->actingAs($user)
        // Make a GET request to the watchlist index route
        ->get(route('watchlist.index'));

    $response->assertOK()
        ->assertSee("My Favourite Cars");
});

