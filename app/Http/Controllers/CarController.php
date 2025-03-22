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
    public function index(Request $request)
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
            ->paginate(15); // Get the results

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

        // Assign request->all() to variable called $data
        $data = $request->all();

        // Get features data
        $featuresData = $data['features'];

        // Provide User ID
        $data['user_id'] = 1;

        // On Car call create method and provide the data
        $car = Car::create($data);

        // Create features
        $car->features()->create($featuresData);

        // Redirect to car.index route
        return redirect()->route('car.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Car $car)
    {
        // If car is 'published_at' does not exist
        if (!$car->published_at) {
            // Use not found method
            abort(404);
        }

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

    public function search(Request $request) // Import request object
    {
        // Get request data in correct format
        $manufacturer = $request->integer('manufacturer_id');
        $model = $request->integer('model_id');
        $carType = $request->integer('car_type_id');
        $fuelType = $request->integer('fuel_type_id');
        $province = $request->integer('province_id');
        $city = $request->integer('city_id');
        $yearFrom = $request->integer('year_from');
        $yearTo = $request->integer('year_to');
        $priceFrom = $request->integer('price_from');
        $priceTo = $request->integer('price_to');
        $mileage = $request->integer('mileage');
        $sort = $request->input('sort', '-published_at');

        // Get the query builder instance with conditions
        $query = Car::where('published_at', '<', now()) // Only show cars that are published
            ->with([ // Eager load the relationships
                'city',
                'carType',
                'fuelType',
                'manufacturer',
                'model',
                'primaryImage'
            ]);

        // Filtering is done after the initial query!
        if ($manufacturer) { // If manufacturer is recieved
            $query->where('manufacturer_id', $manufacturer); // then where manufacturer
        }
        if ($model) {
            $query->where('model_id', $model);
        }
        /**
         ** Filtering by province is tricky, because cars do not have a `province_id`
         ** Here we need to implement a join to
         **/
        if ($province) { // If province is recieved
            $query->join( // Do a join on
                'cities', // 'cities'
                'cities.id', // where 'cities.id`
                '=', // is equal to
                'cars.city_id' // 'cars.city_id'
            )
                ->where( // where
                    'cities.province_id', // 'cities.province_id'
                    $province
                ); // matches the $province from the search
        }
        if ($city) {
            $query->where('city_id', $city);
        }
        if ($carType) {
            $query->where('car_type_id', $carType);
        }
        if ($fuelType) {
            $query->where('fuel_type_id', $fuelType);
        }
        if ($yearFrom) {
            $query->where('year', '>=', $yearFrom);
        }
        if ($yearTo) {
            $query->where('year', '<=', $yearTo);
        }
        if ($priceFrom) {
            $query->where('price', '>=', $priceFrom);
        }
        if ($priceTo) {
            $query->where('price', '<=', $priceTo);
        }
        if ($mileage) {
            $query->where('mileage', '<=', $mileage);
        }

        // If sorting starts with '-'
        if (str_starts_with($sort, '-')) {
            // Take field name without the '-' and put into $sortBy
            $sortBy = substr($sort, 1);
            // On search query call OrderBy descending
            $query->orderBy($sortBy, 'desc');
        } else {
            // Else OrderBy ascending
            $query->orderBy($sort);
        }

        $cars = $query
            ->paginate(15)
            ->withQueryString();

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
            ->paginate(15); // Get the results

        return view(
            'car.watchlist', // Return the view
            [
                'cars' => $cars // Pass the cars to the view
            ]
        );
    }
}
