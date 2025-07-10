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

// Test for accessing the My Cars page as an unauthenticated user
it('should not be possible to access my cars page as guest user', function () {
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->get(route('car.index'));

    // Assert that the response is a redirect to the login route
    $response->assertRedirectToRoute('login');
    // Assert that the response status is 302 (redirect)
    $response->assertStatus(302);
});

// Test for accessing the My Cars page as an authenticated user
it('should be possible to access my cars page as authenticated user', function () {
    /** @var \Illuminate\Testing\TestResponse $response */

    // Create a user and authenticate
    $user = \App\Models\User::factory()->create();
    // Act as the authenticated user
    $response = $this->actingAs($user)
        // Make a GET request to the car create route
        ->get(route('car.index'));

    $response->assertOK()
        ->assertSee("My Cars");
});

// Test for creating a car with empty data fields
it('should not be possible to create a car with empty data fields', function () {
    // Seed the database with necessary data
    $this->seed();

    // Create a user to associate with the car
    // This is necessary because the car creation form requires a user ID
    // and the user must be authenticated to create a car
    // If the user is not authenticated, the request will fail
    // due to missing user ID in the request
    // This simulates a real-world scenario where a user must be logged in
    // to create a car listing
    $user = \App\Models\User::factory()->create();

    /** @var \Illuminate\Testing\TestResponse $response */
    // Make a POST request to the car store route with empty fields
    // This simulates submitting the form with empty data
    // The fields are set to null to test the validation rules
    // that require these fields to be filled out
    // This will help ensure that the validation rules are working correctly
    // and that the application does not allow creating a car with empty fields
    $response = $this->actingAs($user)->post(route('car.store'), [
        'manufacturer_id' => null,
        'model_id' => null,
        'year' => null,
        'price' => null,
        'vin' => null,
        'mileage' => null,
        'car_type_id' => null,
        'fuel_type_id' => null,
        'province_id' => null,
        'city_id' => null,
        'address' => null,
        'phone' => null,
        'description' => null,
        'published_at' => null,
    ]);

    // Debugging: Check the session data to see what was submitted
    // This can help identify what data was sent in the request
    // $response->ddSession();

    // Assert that the response has validation errors for the required fields
    $response->assertInvalid([
        'manufacturer_id',
        'model_id',
        'year',
        'price',
        'vin',
        'mileage',
        'car_type_id',
        'fuel_type_id',
        'city_id',
        'address',
        'phone',
    ]);
});

// Test for creating a car with invalid data fields
it('should not be possible to create a car with invalid data fields', function () {
    // Seed the database with necessary data
    $this->seed();

    // Create a user to associate with the car
    // This is necessary because the car creation form requires a user ID
    // and the user must be authenticated to create a car
    // If the user is not authenticated, the request will fail
    // due to missing user ID in the request
    // This simulates a real-world scenario where a user must be logged in
    // to create a car listing
    $user = \App\Models\User::factory()->create();

    /** @var \Illuminate\Testing\TestResponse $response */
    // Make a POST request to the car store route with invalid data
    // This simulates submitting the form with invalid data
    // The fields are set to values that do not meet the validation rules
    // This will help ensure that the validation rules are working correctly
    // and that the application does not allow creating a car with invalid data
    // The fields are set to values that are outside the acceptable range
    // or format, such as negative prices, invalid years, etc.
    $response = $this->actingAs($user)->post(route('car.store'), [
        'manufacturer_id' => 100,
        'model_id' => 100,
        'year' => 1800,
        'price' => -100,
        'vin' => '123',
        'mileage' => -1000,
        'car_type_id' => 100,
        'fuel_type_id' => 100,
        'province_id' => 100,
        'city_id' => 100,
        'address' => '123',
        'phone' => '123',
    ]);

    // Debugging: Check the session data to see what was submitted
    // This can help identify what data was sent in the request
    // $response->ddSession();

    // Assert that the response has validation errors for the required fields
    $response->assertInvalid([
        'manufacturer_id',
        'model_id',
        'year',
        'price',
        'vin',
        'mileage',
        'car_type_id',
        'fuel_type_id',
        'city_id',
        'phone',
    ]);
});

// Test for creating a car with valid data
it('should be possible to create a car with valid data', function () {
    // Seed the database with necessary data
    $this->seed();

    // Create a user to associate with the car
    // This is necessary because the car creation form requires a user ID
    // and the user must be authenticated to create a car
    // If the user is not authenticated, the request will fail
    // due to missing user ID in the request
    // This simulates a real-world scenario where a user must be logged in
    // to create a car listing
    $user = \App\Models\User::factory()->create();

    /** @var \Illuminate\Testing\TestResponse $response */
    // Make a POST request to the car store route with invalid data
    // This simulates submitting the form with invalid data
    // The fields are set to values that do not meet the validation rules
    // This will help ensure that the validation rules are working correctly
    // and that the application does not allow creating a car with invalid data
    // The fields are set to values that are outside the acceptable range
    // or format, such as negative prices, invalid years, etc.
    $response = $this->actingAs($user)->post(route('car.store'), [
        'manufacturer_id' => 1,
        'model_id' => 1,
        'year' => 2020,
        'price' => 10000,
        'vin' => '11111111111111111',
        'mileage' => 10000,
        'car_type_id' => 1,
        'fuel_type_id' => 1,
        'province_id' => 1,
        'city_id' => 1,
        'address' => '123 Main Street',
        'phone' => '0123456789',
        'features' => [],
    ]);

    // Debugging: Check the session data to see what was submitted
    // This can help identify what data was sent in the request
    // $response->ddSession();

    // Assert that the response has validation errors for the required fields
    $response->assertRedirectToRoute('car.index')
        ->assertSessionHas('success');

    // Assert that the car was created in the database
    // And that the count of cars in the database is now 101
    $this->assertDatabaseCount('cars', 101);
});
