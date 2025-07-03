<?php

// Test for login page
it('returns success on login page', function () {
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->get(route('login'));

    $response->assertStatus(200);
});

// Test for the signup page
it('returns success on signup page', function () {
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->get(route('signup'));

    $response->assertStatus(200);
});

// Test for the password forgot page
it('returns success on forgot password page', function () {
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->get(route('password.request'));

    $response->assertStatus(200);
});

// Test for accessing the car create page as an unauthenticated user
it('redirects to login page, when accessing car create page as guest user', function () {
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->get(route('car.create'));

    // Assert that the response is a redirect to the login route
    $response->assertRedirectToRoute('login');
    // Assert that the response status is 302 (redirect)
    $response->assertStatus(302);
});

