<?php

// Test for displaying "There are no published cars" on home page
it('should display "There are no published cars" on home page', function () {
    /** @var \Illuminate\Testing\TestResponse $response */

    // Go to the home page
    $response = $this->get('/');

    // Assert that the response status is 200 (OK)
    $response->assertStatus(200)
        // Assert that the response contains the "There are no published cars" message
        ->assertSee("There are no published cars");
});

// Test for displaying published cars on home page
it('should display published cars on home page', function () {
    /** @var \Illuminate\Testing\TestResponse $response */

    // Seed the database with published cars
    $this->seed();

    // Go to the home page
    $response = $this->get('/');

    // Assert that the response status is 200 (OK)
    $response->assertStatus(200)
        // Assert that the response does not contain the "There are no published cars" message
        ->assertDontSee("There are no published cars")
        // Assert that the response has a view with the name 'home.index'
        ->assertViewIs('home.index')
        // Assert that the view has a 'cars' variable
        ->assertViewHas('cars', function ($collection) {
            // Assert that the collection contains 30 cars
            return $collection->count() == 30;
        });
});
