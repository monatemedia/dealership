<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Manufacturer;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Sequence;

class HomeController extends Controller
{
    public function index()
    {
        User::factory()
            ->has(Car::factory()->count(5), 'favouriteCars')
            ->create();

        // Create a user
        // $user = User::factory()->create();

        // Create 5 cars and assign them to the user
        // $cars = Car::factory()->count(5)->create([
        //     'user_id' => $user->id, // Assign user ID
        // ]);

        // Save cars using saveMany() for a hasMany relationship
        // $user->cars()->saveMany($cars);

        // dd($user->cars); // Dump the cars for debugging

        // Return the blade view
        return view('home.index');
    }
}
