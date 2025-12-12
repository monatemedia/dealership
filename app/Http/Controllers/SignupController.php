<?php // app/Http/Controllers/SignupController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SignupController extends Controller
{
    /*
     * Show the signup form
     */
    public function create()
    {
        return view('auth.signup');
    }

    /*
     * Store the new user in the database
     */
    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:255', 'unique:users,phone'],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->max(24)
                    ->numbers()
                    ->mixedCase()
                    ->symbols()
                    ->uncompromised()
            ]
        ]);
        // Create user out of validated request data. Hash password
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password)
        ]);
        // Send Email Verification
        event(new Registered($user));
        // Log the user in
        // This will automatically create a session for the user
        // and set the user as the authenticated user
        // This is done so that the user can access the home page
        // and see the success message
        // without having to log in again
        Auth::login($user);
        // Redirect to home page with flash message
        return redirect()->route('home')
            ->with('success', 'Account successful. Please check your email.');
    }
}
