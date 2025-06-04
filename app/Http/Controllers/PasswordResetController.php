<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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
        return back()->with('success', 'Password reset email was sent');
    }

    // Shows the Reset Password form
    public function showResetPassword()
    {
        return view('auth.reset-password');
    }
    // Handles the submitted Reset Password form
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => [
                'required',
                'string',
                'confirmed',
                \Illuminate\Validation\Rules\Password::min(8)
                    ->max(24)
                    ->numbers()
                    ->mixedCase()
                    ->symbols()
                    ->uncompromised()
            ]
        ]);
        $status = Password::reset(
            $request->only(['email', 'password', 'password_confirmation', 'token']),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );
        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', __($status));
        }
        return back()->withErrors(['email' => __($status)]);
    }
}
