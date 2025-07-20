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

// Test for login with incorrect credentials
it('should not be possible to login with incorrect credentials', function () {
    // Create a user using the factory
    \App\Models\User::factory()->create([
        // Use a valid email and password for the test
        'email' => 'edward@gmail.com',
        'password' => bcrypt('password'), // Ensure the password is hashed
    ]);

    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->post(route('login.store'), [
        // Use the same email and password as the created user
        'email' => 'edward@gmail.com',
        'password' => 'wrong-password',
    ]);

    // Assert that the response status is 302 (redirect)
    $response->assertStatus(302)
        // Assert that the session has an 'email' key
        // ->assertSessionHasErrors(['email']);
        ->assertInvalid(['email']);
});

// Test for login with correct credentials
it('should be possible to login with correct credentials', function () {
    // Create a user using the factory
    \App\Models\User::factory()->create([
        // Use a valid email and password for the test
        'email' => 'edward@gmail.com',
        'password' => bcrypt('password'), // Ensure the password is hashed
    ]);

    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->post(route('login.store'), [
        // Use the same email and password as the created user
        'email' => 'edward@gmail.com',
        'password' => 'password',
    ]);

    // Assert that the response status is 302 (redirect)
    $response->assertStatus(302)
        // Assert that the user is redirected to the home page
        ->assertRedirectToRoute('home')
        // Assert that the session has an 'email' key
        ->assertSessionHas(['success']);
});

// Test for the signup page
it('returns success on signup page', function () {
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->get(route('signup'));

    // Check if the response status is 200
    $response->assertStatus(200)
        // Check if the response contains the text 'Signup'
        ->assertSee('Signup')
        // Check if the response contains the text 'Click here to login'
        ->assertSee('Click here to login')
        // Check if the response contains the text 'Google'
        ->assertSee('Google')
        // Check if the response contains the text 'Facebook'
        ->assertSee('Facebook')
        // Check if the response contains a link to the login page
        ->assertSee('<a href="' . route('login') . '"', false)
        // Check if the response contains links to the OAuth login pages for Google and Facebook
        ->assertSee(route('login.oauth', ['provider' => 'google']), false)
        ->assertSee(route('login.oauth', ['provider' => 'facebook']), false);
});

// Test for signup with empty data
it('should not be possible to signup with empty data', function () {

    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->post(route('signup.store'), [
        'name' => '', // Empty name to trigger validation error
        'email' => '', // Empty email to trigger validation error
        'phone' => '', // Empty phone number to trigger validation error
        'password' => '', // Empty password to trigger validation error
        'password_confirmation' => '', // Empty password confirmation to trigger validation error
    ]);

    // Assert that the response status is 302 (redirect)
    $response->assertStatus(302)
        // Assert that the session has an 'email' key
        // ->assertSessionHasErrors(['email']);
        ->assertInvalid(['name', 'email', 'phone', 'password']);
});

// Test for signup with incorrect password
it('should not be possible to signup with incorrect password', function () {

    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->post(route('signup.store'), [
        'name' => 'Edward', // Valid name
        'email' => 'edward@gmail.com', // Invalid email to trigger validation error
        'phone' => '123',
        'password' => '123456',
        'password_confirmation' => '111111', // Password confirmation to trigger validation error
    ]);

    // Assert that the response status is 302 (redirect)
    $response->assertStatus(302)
        // Assert that the session has an 'email' key
        // ->assertSessionHasErrors(['email']);
        ->assertInvalid(['password']);
});

// Test for signup with existing email
it('should not be possible to signup with an existing email', function () {

    // Create a user using the factory
    \App\Models\User::factory()->create([
        'email' => 'edward@gmail.com'
    ]);

    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->post(route('signup.store'), [
        'name' => 'Edward', // Valid name
        'email' => 'edward@gmail.com', // Attempting to use an existing email
        'phone' => '123',
        'password' => '1asda523Aa.#', // Valid password
        'password_confirmation' => '1asda523Aa.#',
    ]);

    // Assert that the response status is 302 (redirect)
    $response->assertStatus(302)
        // Assert that the session has an 'email' key
        ->assertInvalid(['email']);
});

// Test for signup with correct credentials
it('should be possible to signup with correct credentials', function () {

    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->post(route('signup.store'), [
        'name' => 'Edward',
        'email' => 'edward@gmail.com',
        'phone' => '123',
        'password' => 'Dajhdga12312@#',
        'password_confirmation' => 'Dajhdga12312@#',
    ]);

    // Dump the session data for debugging
    // $response->ddSession();

    // Assert that the response status is 302 (redirect)
    $response->assertStatus(302)
        // Assert that the user is redirected to the home page
        ->assertRedirectToRoute('home')
        ->assertSessionHas(['success']);
});

// Test for the forgot password page
it('returns success on forgot password page', function () {
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->get(route('password.request'));

    // Check if the response status is 200
    $response->assertStatus(200)
        // Check if the response contains the text 'Request Password Reset'
        ->assertSee('Request Password Reset')
        // Check if the response contains the text 'Click here to login'
        ->assertSee('Click here to login')
        // Check if the response contains a link to the login page
        ->assertSee('<a href="' . route('login') . '"', false);
});

// Test for password reset with invalid email
it('should not be possible to request password reset with invalid email', function () {

    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->post(route('password.email'), [
        // Use an invalid email to trigger validation error
        'email' => 'not-a-valid-email',
    ]);

    // $response->ddSession();

    // Assert that the response status is 302 (redirect)
    $response->assertStatus(302)
        // Assert that the session has an 'email' key
        ->assertSessionHasErrors(['email']);
});

// Test for password reset with correct email
it('should be possible to request password reset with correct email', function () {
    // Create a user using the factory
    \App\Models\User::factory()->create([
        'email' => 'edward@gmail.com',
    ]);

    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->post(route('password.email'), [
        // Use the same email and password as the created user
        'email' => 'edward@gmail.com',
    ]);

    // Assert that the response status is 302 (redirect)
    $response->assertStatus(302)
        // Assert that the session has an 'email' key
        // ->assertSessionHasErrors(['email']);
        ->assertSessionHas(['success']);
});

// Test for displaying Signup and Login links for guest user
it('should display Signup and Login links for guest user', function () {
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->get(route('home'));

    // Assert that the response status is 200
    $response->assertStatus(200)
        // Assert that the response contains the Signup link
        ->assertSeeInOrder([
            '<a href="' . route('signup') . '"',
            'Signup',
        ], false)
        // Assert that the response contains the Login link
        ->assertSeeInOrder([
            '<a href="' . route('login') . '"',
            'Login',
        ], false);
});
