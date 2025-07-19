<?php

use App\Models\Car;
use App\Models\CarImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use function PHPUnit\Framework\assertEquals;

// Test for accessing the car create page as an unauthenticated user
it('should not be possible to access car create page as guest user', function () {
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->get(route('car.create'));

    // Assert that the response is a redirect to the login route
    $response->assertRedirectToRoute('login');
    // Assert that the response status is 302 (redirect)
    $response->assertStatus(302);
});

// Test for accessing the car create page as an authenticated user
it('should be possible to access car create page as authenticated user', function () {
    /** @var \Illuminate\Testing\TestResponse $response */

    // Create a user and authenticate
    $user = \App\Models\User::factory()->create();
    // Act as the authenticated user
    $response = $this->actingAs($user)
        // Make a GET request to the car create route
        ->get(route('car.create'));

    $response->assertOK()
        ->assertSee('Add new car');
});

// Test for accessing the My Cars page as an unauthenticated user
it('should not be possible to access my cars page as guest user', function () {
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->get(route('car.index'));

    // Assert that the response is a redirect to the login route
    $response->assertRedirectToRoute('login');
    // Assert that the response status is 302 (redirect)
    $response->assertStatus(302);
});

// Test for accessing the My Cars page as an authenticated user
it('should be possible to access my cars page as authenticated user', function () {
    /** @var \Illuminate\Testing\TestResponse $response */

    // Create a user and authenticate
    $user = \App\Models\User::factory()->create();
    // Act as the authenticated user
    $response = $this->actingAs($user)
        // Make a GET request to the car create route
        ->get(route('car.index'));

    $response->assertOK()
        ->assertSee("My Cars");
});

// Test for creating a car with empty data fields
it('should not be possible to create a car with empty data fields', function () {
    // Seed the database with necessary data
    $this->seed();

    // Create a user to associate with the car
    // This is necessary because the car creation form requires a user ID
    // and the user must be authenticated to create a car
    // If the user is not authenticated, the request will fail
    // due to missing user ID in the request
    // This simulates a real-world scenario where a user must be logged in
    // to create a car listing
    $user = \App\Models\User::factory()->create();

    /** @var \Illuminate\Testing\TestResponse $response */
    // Make a POST request to the car store route with empty fields
    // This simulates submitting the form with empty data
    // The fields are set to null to test the validation rules
    // that require these fields to be filled out
    // This will help ensure that the validation rules are working correctly
    // and that the application does not allow creating a car with empty fields
    $response = $this->actingAs($user)->post(route('car.store'), [
        'manufacturer_id' => null,
        'model_id' => null,
        'year' => null,
        'price' => null,
        'vin' => null,
        'mileage' => null,
        'car_type_id' => null,
        'fuel_type_id' => null,
        'province_id' => null,
        'city_id' => null,
        'address' => null,
        'phone' => null,
        'description' => null,
        'published_at' => null,
    ]);

    // Debugging: Check the session data to see what was submitted
    // This can help identify what data was sent in the request
    // $response->ddSession();

    // Assert that the response has validation errors for the required fields
    $response->assertInvalid([
        'manufacturer_id',
        'model_id',
        'year',
        'price',
        'vin',
        'mileage',
        'car_type_id',
        'fuel_type_id',
        'city_id',
        'address',
        'phone',
    ]);
});

// Test for creating a car with invalid data fields
it('should not be possible to create a car with invalid data fields', function () {
    // Seed the database with necessary data
    $this->seed();

    // Create a user to associate with the car
    // This is necessary because the car creation form requires a user ID
    // and the user must be authenticated to create a car
    // If the user is not authenticated, the request will fail
    // due to missing user ID in the request
    // This simulates a real-world scenario where a user must be logged in
    // to create a car listing
    $user = \App\Models\User::factory()->create();

    /** @var \Illuminate\Testing\TestResponse $response */
    // Make a POST request to the car store route with invalid data
    // This simulates submitting the form with invalid data
    // The fields are set to values that do not meet the validation rules
    // This will help ensure that the validation rules are working correctly
    // and that the application does not allow creating a car with invalid data
    // The fields are set to values that are outside the acceptable range
    // or format, such as negative prices, invalid years, etc.
    $response = $this->actingAs($user)->post(route('car.store'), [
        'manufacturer_id' => 100,
        'model_id' => 100,
        'year' => 1800,
        'price' => -100,
        'vin' => '123',
        'mileage' => -1000,
        'car_type_id' => 100,
        'fuel_type_id' => 100,
        'province_id' => 100,
        'city_id' => 100,
        'address' => '123',
        'phone' => '123',
    ]);

    // Debugging: Check the session data to see what was submitted
    // This can help identify what data was sent in the request
    // $response->ddSession();

    // Assert that the response has validation errors for the required fields
    $response->assertInvalid([
        'manufacturer_id',
        'model_id',
        'year',
        'price',
        'vin',
        'mileage',
        'car_type_id',
        'fuel_type_id',
        'city_id',
        'phone',
    ]);
});

// Test for creating a car with valid data
it('should be possible to create a car with valid data', function () {
    // Seed the database with necessary data
    $this->seed();

    // Count the number of cars and images in the database before the test
    // This is useful to verify that a new car is created after the test
    $countCars = Car::count();
    $countImages = CarImage::count();

    // Create a user to associate with the car
    // This is necessary because the car creation form requires a user ID
    // and the user must be authenticated to create a car
    // If the user is not authenticated, the request will fail
    // due to missing user ID in the request
    // This simulates a real-world scenario where a user must be logged in
    // to create a car listing
    $user = User::factory()->create();

    // Create fake images to upload
    // This simulates uploading images for the car listing
    // The images are created using the UploadedFile::fake() method
    // This allows us to test the file upload functionality without needing actual image files
    $images = [
        UploadedFile::fake()->image('1.jpg'),
        UploadedFile::fake()->image('2.jpg'),
        UploadedFile::fake()->image('3.jpg'),
        UploadedFile::fake()->image('4.jpg'),
        UploadedFile::fake()->image('5.jpg'),
    ];

    // Create features for the car
    // This simulates selecting features for the car listing
    // The features are set to values that are valid according to the validation rules
    $features = [
        'abs' => '1',
        'air_conditioning' => '1',
        'power_windows' => '1',
        'power_door_locks' => '1',
        'cruise_control' => '1',
        'bluetooth_connectivity' => '1',
    ];

    // Create the car data to be submitted
    // This simulates filling out the car creation form with valid data
    // The fields are set to values that meet the validation rules
    // This will help ensure that the car is created successfully
    $carData = [
        'manufacturer_id' => 1,
        'model_id' => 1,
        'year' => 2020,
        'price' => 10000,
        'vin' => '11111111111111111',
        'mileage' => 10000,
        'car_type_id' => 1,
        'fuel_type_id' => 1,
        'province_id' => 1,
        'city_id' => 1,
        'address' => '123 Main Street',
        'phone' => '0123456789',
        'features' => $features,
        'images' => $images,
    ];

    /** @var \Illuminate\Testing\TestResponse $response */
    // Make a POST request to the car store route with invalid data
    // This simulates submitting the form with invalid data
    // The fields are set to values that do not meet the validation rules
    // This will help ensure that the validation rules are working correctly
    // and that the application does not allow creating a car with invalid data
    // The fields are set to values that are outside the acceptable range
    // or format, such as negative prices, invalid years, etc.
    $response = $this->actingAs($user)->post(route('car.store'), $carData);

    // Debugging: Check the session data to see what was submitted
    // This can help identify what data was sent in the request
    // $response->ddSession();

    // Assert that the response has validation errors for the required fields
    $response->assertRedirectToRoute('car.index')
        ->assertSessionHas('success');

    // Get the last car created in the database
    $lastCar = \App\Models\Car::latest('id')->first();

    // Add the car ID to the features array
    // This is necessary to associate the features with the car
    // The car ID is used to link the features to the specific car listing
    $features['car_id'] = $lastCar->id;

    // Add the car ID to the car data
    // This is necessary to associate the car data with the car
    // The car ID is used to link the car data to the specific car listing
    // This allows us to assert the car data in the database later
    // This is important for ensuring that the car data is correctly associated
    $carData['id'] = $lastCar->id;

    // Unset the features and images from the car data
    // This is necessary because the features and images are stored in separate tables
    // and should not be included in the car data when asserting the database
    // The car data should only contain the fields that are directly related to the car
    unset($carData['features']);
    unset($carData['images']);
    unset($carData['province_id']);

    // Assert that the car was created in the database
    // And that the count of cars in the database is now 101
    $this->assertDatabaseCount('cars', $countCars + 1);
    // Assert that the count of images in the database is now 505
    $this->assertDatabaseCount('car_images', $countImages + count($images));
    // Assert that the car features were created in the database
    $this->assertDatabaseCount('car_features', $countCars + 1);
    // Assert that the car was created in the database with the correct values
    $this->assertDatabaseHas('cars', $carData);
    // Assert that the car features were created in the database with the correct values
    $this->assertDatabaseHas('car_features', $features);
});

// Test for displaying the update car page with correct data
it('should display update car page with correct data', function () {
    // Seed the database with necessary data
    $this->seed();

    // Select he first user from the database
    $user = User::first();

    // Select the first car associated with the user
    $firstCar = $user->cars()->first();

    // Access the car edit page as the authenticated user
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->actingAs($user)
        ->get(route('car.edit', $firstCar->id));

    // Assert that we can see the car edit page
    $response->assertSee("Edit Car:");

    // Assert that the response contains the car's manufacturer select dropdown
    // and that it has the correct manufacturer ID selected
    // Assert that the model select dropdown is rendered
    $response->assertSee('<select id="manufacturerSelect" name="manufacturer_id">', false);
    // Use regex to confirm the correct manufacturer is selected in the correct option tag
    preg_match(
        '/<option\s+value="' . $firstCar->manufacturer_id . '"\s+selected[^>]*>\s*' . preg_quote($firstCar->manufacturer->name, '/') . '\s*<\/option>/',
        $response->getContent(),
        $matches
    );
    // Use Pest assertions
    expect($matches)->not->toBeEmpty(); // Pest
    // OR
    // $this->assertNotEmpty($matches); // PHPUnit

    // Assert that the response contains the car's model select dropdown
    // and that it has the correct model ID selected
    // Assert that the model select dropdown is rendered
    $response->assertSee('<select id="modelSelect" name="model_id">', false);
    // Use regex to confirm the correct model is selected in the correct option tag
    preg_match(
        '/<option\s+value="' . $firstCar->model_id . '"[^>]*selected[^>]*>\s*' . preg_quote($firstCar->model->name, '/') . '\s*<\/option>/',
        $response->getContent(),
        $matches
    );
    // Use Pest assertions
    expect($matches)->not->toBeEmpty(); // Pest
    // OR
    // $this->assertNotEmpty($matches); // PHPUnit

    // Assert that the response contains the car's year select dropdown
    $response->assertSee('<select name="year">', false);
    // Use regex to confirm the correct year is selected
    preg_match(
        '/<option\s+value="' . preg_quote($firstCar->year, '/') . '"[^>]*selected[^>]*>\s*' . preg_quote($firstCar->year, '/') . '\s*<\/option>/',
        $response->getContent(),
        $matches
    );
    // Pest
    expect($matches)->not->toBeEmpty();
    // PHPUnit alternative:
    // $this->assertNotEmpty($matches);

    // Assert that the response contains the car's type radio button
    // and that at least one exists
    // Use regex to confirm the correct car type radio button is checked
    preg_match(
        '/<label[^>]*>\s*<input\s+[^>]*name="car_type_id"[^>]*value="' . preg_quote($firstCar->car_type_id, '/') . '"[^>]*checked[^>]*>\s*' . preg_quote($firstCar->carType->name, '/') . '\s*<\/label>/i',
        $response->getContent(),
        $matches
    );
    // Pest
    expect($matches)->not->toBeEmpty();
    // PHPUnit alternative:
    // $this->assertNotEmpty($matches);

    // Assert that the response contains the car's price
    $response->assertSeeInOrder([
        'name="price"',
        ' value="' . $firstCar->price . '"',
    ], false);

    // Assert that the response contains the car's VIN
    $response->assertSeeInOrder([
        'name="vin"',
        ' value="' . $firstCar->vin . '"',
    ], false);

    // Assert that the response contains the car's mileage
    $response->assertSeeInOrder([
        'name="mileage"',
        ' value="' . $firstCar->mileage . '"',
    ], false);

    // Assert that the response contains the car's fuel type radio button
    // and that at least one exists
    // Use regex to confirm the correct fuel type radio button is checked
    preg_match(
        '/<label[^>]*>\s*<input\s+[^>]*name="fuel_type_id"[^>]*value="' . preg_quote($firstCar->fuel_type_id, '/') . '"[^>]*checked[^>]*>\s*' . preg_quote($firstCar->fuelType->name, '/') . '\s*<\/label>/i',
        $response->getContent(),
        $matches
    );
    // Pest
    expect($matches)->not->toBeEmpty();
    // PHPUnit alternative:
    // $this->assertNotEmpty($matches);

    // Assert that the response contains the car's province select dropdown
    // and that it has the correct province ID selected
    // Assert that the model select dropdown is rendered
    $response->assertSee('<select id="provinceSelect" name="province_id">', false);
    // Use regex to confirm the correct province is selected in the correct option tag
    preg_match(
        '/<option\s+value="' . $firstCar->city->province_id . '"\s+selected[^>]*>\s*' . preg_quote($firstCar->city->province->name, '/') . '\s*<\/option>/',
        $response->getContent(),
        $matches
    );
    // Use Pest assertions
    expect($matches)->not->toBeEmpty(); // Pest
    // OR
    // $this->assertNotEmpty($matches); // PHPUnit

    // Assert that the response contains the car's city select dropdown
    // and that it has the correct city ID selected
    // Assert that the city dropdown is rendered
    $response->assertSee('<select id="citySelect" name="city_id">', false);
    // Confirm the correct city option is selected and structured properly
    preg_match(
        '/<option[^>]*value="' . $firstCar->city_id . '"[^>]*data-parent="' . $firstCar->city->province_id . '"[^>]*selected[^>]*>\s*' .
        preg_quote($firstCar->city->name, '/') .
        '\s*<\/option>/i',
        $response->getContent(),
        $matches
    );
    // Use Pest assertions
    expect($matches)->not->toBeEmpty(); // Pest
    // OR
    // $this->assertNotEmpty($matches); // PHPUnit

    // Assert that the response contains the car's address
    $response->assertSeeInOrder([
        'name="address"',
        ' value="' . $firstCar->address . '"',
    ], false);

    // Assert that the response contains the car's phone
    $response->assertSeeInOrder([
        'name="phone"',
        ' value="' . $firstCar->phone . '"',
    ], false);

    // Create an array of feature keys to check
    // This array contains the keys for the features that should be present
    $featureKeys = [
        'air_conditioning',
        'power_windows',
        'power_door_locks',
        'abs',
        'cruise_control',
        'bluetooth_connectivity',
        'remote_start',
        'gps_navigation',
        'heated_seats',
        'climate_control',
        'rear_parking_sensors',
        'leather_seats',
    ];
    // Assert that the response contains the car's features checkboxes
    // Loop through each feature key and check if the checkbox is present
    foreach ($featureKeys as $key) {
        $featureName = 'name="features[' . $key . ']"';
        $featureValue = 'value="1"';
        // If the feature is present in the car's features
        if (!empty($firstCar->features[$key])) {
            // Assert the feature checkbox is checked
            $response->assertSeeInOrder([
                '<label class="checkbox">',
                '<input',
                'type="checkbox"',
                $featureName,
                $featureValue,
                'checked',
            ], false);
        } else {
            // Assert the feature checkbox is NOT checked
            $response->assertSeeInOrder([
                '<label class="checkbox">',
                '<input',
                'type="checkbox"',
                $featureName,
                $featureValue,
            ], false);
            // And explicitly assert "checked" does not appear in the same input
            $pattern = '/<input[^>]*' . preg_quote($featureName, '/') . '[^>]*' . preg_quote($featureValue, '/') . '[^>]*checked[^>]*>/i';
            expect(preg_match($pattern, $response->getContent()))->toBe(0);
        }
    }

    // Assert that the response contains the car's description textarea
    // Assert that the label is present
    $response->assertSee('<label>Detailed Description</label>', false);
    // Assert that the textarea is present with the car's description
    $response->assertSeeInOrder([
        '<textarea',
        'name="description">' . $firstCar->description . '</textarea>',
    ], false);

    // Assert that the response contains the car's published_at label
    // Assert that the label is present
    $response->assertSee('<label>Publish Date</label>', false);
    // Assert that the response contains the car's published_at input
    $response->assertSeeInOrder([
        '<input',
        'type="date"',
        'name="published_at"',
        'value="' . $firstCar->published_at->format('Y-m-d') . '"',
    ], false);
});

// Test for successfully updating car details
it('should successfully update car details', function () {
    // Seed the database with necessary data
    $this->seed();

    // Count the number of cars in the database before the test
    // This is useful to verify that no new car is created after the test
    // and that the existing car is updated
    $countCars = Car::count();

    // Create a user to associate with the car
    // This is necessary because the car update form requires a user ID
    // and the user must be authenticated to update a car
    // If the user is not authenticated, the request will fail
    // due to missing user ID in the request
    $user = User::first();

    // dd($user)

    // Select the first car associated with the user
    // This is necessary to ensure that the car being updated belongs to the user
    // If the user does not have any cars, this will return null
    // and the test will fail
    $firstCar = $user->cars()->first();

    // dd($firstCar);

    $features = [
        'abs' => '1',
        'air_conditioning' => '1',
        'power_windows' => '1',
        'power_door_locks' => '1',
        'cruise_control' => '1',
        'bluetooth_connectivity' => '1',
    ];

    // Create the car data to be submitted
    // This simulates filling out the car creation form with valid data
    // The fields are set to values that meet the validation rules
    // This will help ensure that the car is created successfully
    $carData = [
        'manufacturer_id' => 1,
        'model_id' => 1,
        'year' => 2020,
        'price' => 10000,
        'vin' => '11111111111111111',
        'mileage' => 10000,
        'car_type_id' => 1,
        'fuel_type_id' => 1,
        'province_id' => 1,
        'city_id' => 1,
        'address' => '123 Main Street',
        'phone' => '0123456789',
        'features' => $features,
    ];

    /** @var \Illuminate\Testing\TestResponse $response */
    // Make a PUT request to the car store route with invalid data
    // This simulates submitting the form with invalid data
    // The fields are set to values that do not meet the validation rules
    // This will help ensure that the validation rules are working correctly
    // and that the application does not allow creating a car with invalid data
    // The fields are set to values that are outside the acceptable range
    // or format, such as negative prices, invalid years, etc.
    $response = $this->actingAs($user)->put(route('car.update', $firstCar), $carData);

    // Debugging: Check the session data to see what was submitted
    // This can help identify what data was sent in the request
    // $response->ddSession();

    // Assert that the response has validation errors for the required fields
    $response->assertRedirectToRoute('car.index')
        ->assertSessionHas('success');

    // Add the car ID to the car data
    // This is necessary to associate the car data with the car
    // The car ID is used to link the car data to the specific car listing
    // This allows us to assert the car data in the database later
    $carData['id'] = $firstCar->id;

    // Add the car ID to the features array
    // This is necessary to associate the features with the car
    // The car ID is used to link the features to the specific car listing
    // This allows us to assert the car features in the database later
    // This is important for ensuring that the car features are correctly associated
    // with the car being updated
    $features['car_id'] = $firstCar->id;

    // Remove non-db fields before DB check
    unset($carData['features'], $carData['province_id']);

    // Assert that the car was updated in the database
    // And that the count of cars in the database is still the same
    // This is important to ensure that no new car was created
    // and that the existing car was updated with the new data
    $this->assertDatabaseCount('cars', $countCars);

    // Assert that the car features were created in the database
    $this->assertDatabaseCount('car_features', $countCars);
    // Assert that the car was created in the database with the correct values
    $this->assertDatabaseHas('cars', $carData);
    // Assert that the car features is equal to the number of cars
    $this->assertDatabaseCount('car_features', $countCars);
    // Assert that the car features were created in the database with the correct values
    $this->assertDatabaseHas('car_features', $features);
});

// Test for successfully deleting a car
it('should successfully delete a car', function () {
    // Seed the database with necessary data
    $this->seed();

    // Count the number of cars in the database before the test
    // This is useful to verify that the car is deleted after the test
    $countCars = Car::count();

    // Select the first user from the database
    // This is necessary because the car deletion requires a user ID
    // and the user must be authenticated to delete a car
    // If the user is not authenticated, the request will fail
    // due to missing user ID in the request
    $user = User::first();

    // dd($user)

    // Select the first car associated with the user
    // This is necessary to ensure that the car being deleted belongs to the user
    // If the user does not have any cars, this will return null
    // and the test will fail
    $firstCar = $user->cars()->first();

    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->actingAs($user)
        ->delete(route('car.destroy', $firstCar));

    $response->assertRedirectToRoute('car.index')
        ->assertSessionHas('success');

    // Assert that the car was deleted from the database
    // And that the count of cars in the database is now one less than before
    $this->assertDatabaseHas('cars', [
        'id' => $firstCar->id,
        'deleted_at' => now(),
    ]);
});

// Test for uploading more images to a car
it('should upload more images on the car', function () {
    // Seed the database with necessary data
    $this->seed();

    // Select the first user from the database
    $user = User::first();

    // Select the first car associated with the user
    $firstCar = $user->cars()->first();

    $oldCount = $firstCar->images()->count();

    // Create fake images to upload
    // This simulates uploading images for the car listing
    // The images are created using the UploadedFile::fake() method
    // This allows us to test the file upload functionality without needing actual image files
    $images = [
        UploadedFile::fake()->image('1.jpg'),
        UploadedFile::fake()->image('2.jpg'),
        UploadedFile::fake()->image('3.jpg'),
        UploadedFile::fake()->image('4.jpg'),
        UploadedFile::fake()->image('5.jpg'),
    ];

    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->actingAs($user)
        ->post(route('car.addImages', $firstCar), [
            'images' => $images,
        ]);

    $response->assertRedirectToRoute('car.images', $firstCar)
        ->assertSessionHas('success');

    $newCount = $firstCar->images()->count();

    $this->assertEquals($newCount, $oldCount + count($images));
});

// Test for successfully deleting images on the car
it('should successfully delete images on the car', function () {
    // Seed the database with necessary data
    $this->seed();

    // Select the first user from the database
    $user = User::first();

    // Select the first car associated with the user
    $firstCar = $user->cars()->first();

    // Count the number of images associated with the car before deletion
    $oldCount = $firstCar->images()->count();

    // Get the IDs of the first two images associated with the car
    $ids = $firstCar->images()->limit(2)->pluck('id')->toArray();

    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->actingAs($user)
        ->put(route('car.updateImages', $firstCar), [
            'delete_images' => $ids,
        ]);

    // Assert that the response is a redirect
    // This indicates that the images were successfully deleted
    // and the user is redirected to the car images route
    $response->assertStatus(302);

    // Count the number of images associated with the car after deletion
    $newCount = $firstCar->images()->count();

    // Assert that the number of images after deletion is equal to the
    // old count minus the number of deleted images
    $this->assertEquals($newCount, $oldCount - 2);

    // Assert that the response redirects to the car images route with
    // the first car
    $response->assertRedirectToRoute('car.images', $firstCar)
        ->assertSessionHas('success');

    // Assert that the number of images in the database is equal to
    // the new count
    $newCount = $firstCar->images()->count();

    // Assert that the number of images after deletion is equal to
    // the old count minus the number of deleted images
    $this->assertEquals($newCount, $oldCount - 2);
});

// Test for successfully updating image positions on the car
it('should successfully update image positions', function () {
    // Seed the database with necessary data
    $this->seed();

    // Select the first user from the database
    $user = User::first();

    // Select the first car associated with the user
    $firstCar = $user->cars()->first();

    // Get all the images associated with the car
    // and reorder them by position in descending order
    // This is useful to ensure that the images are in the correct order
    // before updating their positions
    $images = $firstCar->images()->reorder('position', 'desc')->get();

    // Create an array to hold the new positions
    $data = [];

    // Loop through each image and assign a new position
    // The new position is set to the index of the image in the array plus one
    // This ensures that the positions are sequential starting from 1
    // This is important for maintaining the order of the images
    foreach ($images as $i => $image) {
        $data[$image->id] = $i + 1;
    }

    // Debugging: Check the data array to see the new positions
    // dump($data);

    // Make a PUT request to the car update images route with the new positions
    // This simulates submitting the form with the new image positions
    // The positions are set to the values in the $data array
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->actingAs($user)
        ->put(route('car.updateImages', $firstCar), [
            'positions' => $data,
        ]);

    // Assert that the response is a redirect
    // This indicates that the image positions were successfully updated
    // and the user is redirected to the car images route
    // This is important to ensure that the user is informed of the successful update
    $response->assertRedirectToRoute('car.images', $firstCar)
        ->assertSessionHas('success');

    // Assert that the database has the updated image positions
    // This checks that each image ID in the $data array has the correct position
    foreach ($data as $id => $position) {
        $this->assertDatabaseHas('car_images', [
            'id' => $id,
            'position' => $position,
        ]);
    }
});
