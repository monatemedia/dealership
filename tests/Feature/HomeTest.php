<?php

// Test for displaying "There are no published vehicles" on home page
it('should display "There are no published vehicles" on home page', function () {
    /** @var \Illuminate\Testing\TestResponse $response */

    // Go to the home page
    $response = $this->get('/');

    // Assert that the response status is 200 (OK)
    $response->assertStatus(200)
        // Assert that the response contains the "There are no published vehicles" message
        ->assertSee("There are no published vehicles");
});

// Test for displaying published vehicles on home page
it('should display published vehicles on home page', function () {
    /** @var \Illuminate\Testing\TestResponse $response */

    // Seed the database with published vehicles
    $this->seed();

    // Go to the home page
    $response = $this->get('/');

    // Assert that the response status is 200 (OK)
    $response->assertStatus(200)
        // Assert that the response does not contain the "There are no published vehicles" message
        ->assertDontSee("There are no published vehicles")
        // Assert that the response has a view with the name 'home.index'
        ->assertViewIs('home.index')
        // Assert that the view has a 'vehicles' variable
        ->assertViewHas('vehicles', function ($collection) {
            // Assert that the collection contains 30 vehicles
            return $collection->count() == 30;
        });
});
