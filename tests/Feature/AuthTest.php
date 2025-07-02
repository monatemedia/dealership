<?php

// Test for login page
it('returns success on login page', function () {
    $response = $this->get(route('login'));

    $response->assertStatus(200);
});

// Test for the signup page
it('returns success on signup page', function () {
    $response = $this->get(route('signup'));

    $response->assertStatus(200);
});

// Test for the password forgot page
it('returns success on forgot password page', function () {
    $response = $this->get(route('password.request'));

    $response->assertStatus(200);
});
