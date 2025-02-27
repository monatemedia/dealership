<?php

namespace App\Http\Controllers;

use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        // Select the user
        $user = User::find(1);

        // Delete cars from favourites with IDS: 1, 2, 3
        $user->favouriteCars()->detach([1, 2, 3]);

        // Select and print all cars this $user has added into his favourites watchlist
        dd($user->favouriteCars);

        // Return the blade view
        return view('home.index');
    }
}
