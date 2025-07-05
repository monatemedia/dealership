<?php

// Test for login page
it('returns success on login page', function () {
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->get(route('login'));

    // Check if the response status is 200
    $response->assertStatus(200)
        // Check if the response contains the text 'Login'
        ->assertSee('Login')
        // Check if the response contains the text 'Forgot Password'
        ->assertSee('Forgot Password')
        // Check if the response contains the text 'Click here to create one'
        ->assertSee('Click here to create one')
        // Check if the response contains the text 'Google'
        ->assertSee('Google')
        // Check if the response contains the text 'Facebook'
        ->assertSee('Facebook')
        // Check if the response contains a link to the forgot password page
        ->assertSee('<a href="' . route('password.request') . '"', false)
        // Check if the response contains a link to the signup page
        ->assertSee('<a href="' . route('signup') . '"', false)
        // Check if the response contains links to the OAuth login pages for Google and Facebook
        ->assertSee(route('login.oauth', ['provider' => 'google']), false)
        ->assertSee(route('login.oauth', ['provider' => 'facebook']), false);
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



