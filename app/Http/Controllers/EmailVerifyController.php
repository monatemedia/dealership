<?php // app/Http/Controller.php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class EmailVerifyController extends Controller
{
    /**
     * Summary of verify - Will be called when user clicks on the verification link in email
     * @param \Illuminate\Foundation\Auth\EmailVerificationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(EmailVerificationRequest $request)
    {
        // The request will be automatically validated by the 'signed' middleware
        // which checks if the link is valid and not tampered with
        // If the link is valid, the user will be marked as verified
        // and the email_verified_at timestamp will be set to the current time
        $request->fulfill();

        // Redirect the user to the home page with a success message
        return redirect()->route('home')
            ->with('success', 'Email verified. You can now add vehicles!');
    }

    public function notice()
    {
        // Will be called if we setup verified middleware, so that only
        // verified users are able to access certain routes
        return view('auth.verify-email');
    }

    /**
     * Sends a new email verification notification.
     * * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        // This method will be called when the user clicks on the "Resend Verification Email" button
        // It will send a new verification email to the user
        // The user must be authenticated to access this method

        /** @var User $user */
        // Ensure the user is authenticated
        $user = $request->user();

        // Check if the user is already verified
        // NOTE: In standard Laravel, this is typically handled by middleware,
        // but ensuring no duplicate email is sent is good practice.
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home')
            ->with('info', 'Please check your email.');
        }

        $user->sendEmailVerificationNotification();

        // MODIFICATION: Redirect to 'home.index' with 'info' flash message as requested.
        return redirect()
            ->route('home')
            ->with('info', 'Please check your email.');
    }
}
