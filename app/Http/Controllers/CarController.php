<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Find cars for authenticated user
        // TODO We'll come back to this later
        $cars = User::find(5) // Select the user
            ->cars() // Select the cars that belong to the user
            ->orderBy('created_at', 'desc') // Order by created_at column
            ->get(); // Get the results

        return view(
            'car.index', // Return the view
            [
                'cars' => $cars // Pass the cars to the view
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('car.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        return view('car.show', [
            'car' => $car
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        return view('car.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        //
    }

    public function search()
    {
        // Get the query builder instance with conditions
        $query = Car::where('published_at', '<', now())
            ->orderBy('published_at', 'desc');

        // Get total count of the cars
        $carCount = $query->count();


        // Select 30 cars
        $cars = $query->limit(30)->get();

        return view('car.search', [
            'cars' => $cars,
            'carCount' => $carCount
        ]);
    }

    public function watchlist()
    {
        // Find favourite cars for authenticated user
        // TODO We'll come back to this later
        $cars = User::find(4)->favouriteCars; // Select the user and get the favourite cars
        return view(
            'car.watchlist', // Return the view
            [
                'cars' => $cars // Pass the cars to the view
            ]
        );
    }
}
