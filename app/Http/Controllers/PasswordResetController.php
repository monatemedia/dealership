<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    // Shows the Forgot Password form
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // Handles the submitted Forgot Password form
    public function forgotPassword(Request $request)
    {
        // Validate email
        $request->validate(['email' => ['required', 'email']]);
        // Try to send an email
        $status = Password::sendResetLink($request->only('email'));
        // Redirect user back with success message
        return back()->with('success', 'Password reset email was sent.');
    }

    // Shows the Reset Password form
    public function showResetPassword()
    {
        return view('auth.reset-password');
    }
    // Handles the submitted Reset Password form
    public function resetPassword(Request $request)
    {

    }
}
