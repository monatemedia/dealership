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
        $cars = User::find(1)
            ->cars()
            ->with([
                'manufacturer',
                'model',
                'primaryImage'
            ])
            ->limit(10)
            ->get();

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
        $query = Car::select('cars.*') // Select all columns from the cars table
            ->with([ // Eager load the relationships
                'city',
                'carType',
                'fuelType',
                'manufacturer',
                'model',
                'primaryImage'
            ])
            ->where('published_at', '<', now()) // Only show cars that are published
            ->orderBy('published_at', 'desc'); // Order by the published_at date

        $cars = $query->paginate(5); // Paginate the results

        return view('car.search', [
            'cars' => $cars,
        ]);
    }

    public function watchlist()
    {
        // Find favourite cars for authenticated user
        // TODO We'll come back to this later
        $cars = User::find(4) // Select the user
            ->favouriteCars() // Select the user's favourite cars
            ->with([ // Eager load the relationships
                'city',
                'carType',
                'fuelType',
                'manufacturer',
                'model',
                'primaryImage'
            ])
            ->get(); // Get the results

        return view(
            'car.watchlist', // Return the view
            [
                'cars' => $cars // Pass the cars to the view
            ]
        );
    }
}
