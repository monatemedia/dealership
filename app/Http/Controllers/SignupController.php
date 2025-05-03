<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
        // Redirect to home page with flash message
        return redirect()->route('home')
            ->with('success', 'Account created successfully');
    }
}
