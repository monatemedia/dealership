<?php

use App\Models\Vehicle;
use App\Models\VehicleImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use function PHPUnit\Framework\assertEquals;

// Test for accessing the vehicle create page as an unauthenticated user
it('should not be possible to access vehicle create page as guest user', function () {
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->get(route('vehicle.create'));

    // Assert that the response is a redirect to the login route
    $response->assertRedirectToRoute('login');
    // Assert that the response status is 302 (redirect)
    $response->assertStatus(302);
});

// Test for accessing the vehicle create page as an authenticated user
it('should be possible to access vehicle create page as authenticated user', function () {
    /** @var \Illuminate\Testing\TestResponse $response */

    // Create a user and authenticate
    $user = User::factory()->create();
    // Act as the authenticated user
    $response = $this->actingAs($user)
        // Make a GET request to the vehicle create route
        ->get(route('vehicle.create'));

    $response->assertOK()
        ->assertSee('Add new vehicle');
});

// Test for accessing the My Vehicles page as an unauthenticated user
it('should not be possible to access my vehicles page as guest user', function () {
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->get(route('vehicle.index'));

    // Assert that the response is a redirect to the login route
    $response->assertRedirectToRoute('login');
    // Assert that the response status is 302 (redirect)
    $response->assertStatus(302);
});

// Test for accessing the My Vehicles page as an authenticated user
it('should be possible to access my vehicles page as authenticated user', function () {
    /** @var \Illuminate\Testing\TestResponse $response */

    // Create a user and authenticate
    $user = User::factory()->create();
    // Act as the authenticated user
    $response = $this->actingAs($user)
        // Make a GET request to the vehicle create route
        ->get(route('vehicle.index'));

    $response->assertOK()
        ->assertSee("My Vehicles");
});

// Test for creating a vehicle with empty data fields
it('should not be possible to create a vehicle with empty data fields', function () {
    // Seed the database with necessary data
    $this->seed();

    // Create a user to associate with the vehicle
    // This is necessary because the vehicle creation form requires a user ID
    // and the user must be authenticated to create a vehicle
    // If the user is not authenticated, the request will fail
    // due to missing user ID in the request
    // This simulates a real-world scenario where a user must be logged in
    // to create a vehicle listing
    $user = User::factory()->create();

    /** @var \Illuminate\Testing\TestResponse $response */
    // Make a POST request to the vehicle store route with empty fields
    // This simulates submitting the form with empty data
    // The fields are set to null to test the validation rules
    // that require these fields to be filled out
    // This will help ensure that the validation rules are working correctly
    // and that the application does not allow creating a vehicle with empty fields
    $response = $this->actingAs($user)->post(route('vehicle.store'), [
        'manufacturer_id' => null,
        'model_id' => null,
        'year' => null,
        'price' => null,
        'vin' => null,
        'mileage' => null,
        'vehicle_type_id' => null,
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
        'vehicle_type_id',
        'fuel_type_id',
        'city_id',
        'address',
        'phone',
    ]);
});

// Test for creating a vehicle with invalid data fields
it('should not be possible to create a vehicle with invalid data fields', function () {
    // Seed the database with necessary data
    $this->seed();

    // Create a user to associate with the vehicle
    // This is necessary because the vehicle creation form requires a user ID
    // and the user must be authenticated to create a vehicle
    // If the user is not authenticated, the request will fail
    // due to missing user ID in the request
    // This simulates a real-world scenario where a user must be logged in
    // to create a vehicle listing
    $user = \App\Models\User::factory()->create();

    /** @var \Illuminate\Testing\TestResponse $response */
    // Make a POST request to the vehicle store route with invalid data
    // This simulates submitting the form with invalid data
    // The fields are set to values that do not meet the validation rules
    // This will help ensure that the validation rules are working correctly
    // and that the application does not allow creating a vehicle with invalid data
    // The fields are set to values that are outside the acceptable range
    // or format, such as negative prices, invalid years, etc.
    $response = $this->actingAs($user)->post(route('vehicle.store'), [
        'manufacturer_id' => 100,
        'model_id' => 100,
        'year' => 1800,
        'price' => -100,
        'vin' => '123',
        'mileage' => -1000,
        'vehicle_type_id' => 100,
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
        'vehicle_type_id',
        'fuel_type_id',
        'city_id',
        'phone',
    ]);
});

// Test for creating a vehicle with valid data
it('should be possible to create a vehicle with valid data', function () {
    // Seed the database with necessary data
    $this->seed();

    // Count the number of vehicles and images in the database before the test
    // This is useful to verify that a new vehicle is created after the test
    $countVehicles = Vehicle::count();
    $countImages = VehicleImage::count();

    // Create a user to associate with the vehicle
    // This is necessary because the vehicle creation form requires a user ID
    // and the user must be authenticated to create a vehicle
    // If the user is not authenticated, the request will fail
    // due to missing user ID in the request
    // This simulates a real-world scenario where a user must be logged in
    // to create a vehicle listing
    $user = User::factory()->create();

    // Create fake images to upload
    // This simulates uploading images for the vehicle listing
    // The images are created using the UploadedFile::fake() method
    // This allows us to test the file upload functionality without needing actual image files
    $images = [
        UploadedFile::fake()->image('1.jpg'),
        UploadedFile::fake()->image('2.jpg'),
        UploadedFile::fake()->image('3.jpg'),
        UploadedFile::fake()->image('4.jpg'),
        UploadedFile::fake()->image('5.jpg'),
    ];

    // Create features for the vehicle
    // This simulates selecting features for the vehicle listing
    // The features are set to values that are valid according to the validation rules
    $features = [
        'abs' => '1',
        'air_conditioning' => '1',
        'power_windows' => '1',
        'power_door_locks' => '1',
        'cruise_control' => '1',
        'bluetooth_connectivity' => '1',
    ];

    // Create the vehicle data to be submitted
    // This simulates filling out the vehicle creation form with valid data
    // The fields are set to values that meet the validation rules
    // This will help ensure that the vehicle is created successfully
    $vehicleData = [
        'manufacturer_id' => 1,
        'model_id' => 1,
        'year' => 2020,
        'price' => 10000,
        'vin' => '11111111111111111',
        'mileage' => 10000,
        'vehicle_type_id' => 1,
        'fuel_type_id' => 1,
        'province_id' => 1,
        'city_id' => 1,
        'address' => '123 Main Street',
        'phone' => '0123456789',
        'features' => $features,
        'images' => $images,
    ];

    /** @var \Illuminate\Testing\TestResponse $response */
    // Make a POST request to the vehicle store route with invalid data
    // This simulates submitting the form with invalid data
    // The fields are set to values that do not meet the validation rules
    // This will help ensure that the validation rules are working correctly
    // and that the application does not allow creating a vehicle with invalid data
    // The fields are set to values that are outside the acceptable range
    // or format, such as negative prices, invalid years, etc.
    $response = $this->actingAs($user)->post(route('vehicle.store'), $vehicleData);

    // Debugging: Check the session data to see what was submitted
    // This can help identify what data was sent in the request
    // $response->ddSession();

    // Assert that the response has validation errors for the required fields
    $response->assertRedirectToRoute('vehicle.index')
        ->assertSessionHas('success');

    // Get the last vehicle created in the database
    $lastVehicle = Vehicle::latest('id')->first();

    // Add the vehicle ID to the features array
    // This is necessary to associate the features with the vehicle
    // The vehicle ID is used to link the features to the specific vehicle listing
    $features['vehicle_id'] = $lastVehicle->id;

    // Add the vehicle ID to the vehicle data
    // This is necessary to associate the vehicle data with the vehicle
    // The vehicle ID is used to link the vehicle data to the specific vehicle listing
    // This allows us to assert the vehicle data in the database later
    // This is important for ensuring that the vehicle data is correctly associated
    $vehicleData['id'] = $lastVehicle->id;

    // Unset the features and images from the vehicle data
    // This is necessary because the features and images are stored in separate tables
    // and should not be included in the vehicle data when asserting the database
    // The vehicle data should only contain the fields that are directly related to the vehicle
    unset($vehicleData['features']);
    unset($vehicleData['images']);
    unset($vehicleData['province_id']);

    // Assert that the vehicle was created in the database
    // And that the count of vehicles in the database is now 101
    $this->assertDatabaseCount('vehicles', $countVehicles + 1);
    // Assert that the count of images in the database is now 505
    $this->assertDatabaseCount('vehicle_images', $countImages + count($images));
    // Assert that the vehicle features were created in the database
    $this->assertDatabaseCount('vehicle_features', $countVehicles + 1);
    // Assert that the vehicle was created in the database with the correct values
    $this->assertDatabaseHas('vehicles', $vehicleData);
    // Assert that the vehicle features were created in the database with the correct values
    $this->assertDatabaseHas('vehicle_features', $features);
});

// Test for displaying the update vehicle page with correct data
it('should display update vehicle page with correct data', function () {
    // Seed the database with necessary data
    $this->seed();

    // Select he first user from the database
    $user = User::first();

    // Select the first vehicle associated with the user
    $firstVehicle = $user->vehicles()->first();

    // Access the vehicle edit page as the authenticated user
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->actingAs($user)
        ->get(route('vehicle.edit', $firstVehicle->id));

    // Assert that we can see the vehicle edit page
    $response->assertSee("Edit Vehicle:");

    // Assert that the response contains the vehicle's manufacturer select dropdown
    // and that it has the correct manufacturer ID selected
    // Assert that the model select dropdown is rendered
    $response->assertSee('<select id="manufacturerSelect" name="manufacturer_id">', false);
    // Use regex to confirm the correct manufacturer is selected in the correct option tag
    preg_match(
        '/<option\s+value="' . $firstVehicle->manufacturer_id . '"\s+selected[^>]*>\s*' . preg_quote($firstVehicle->manufacturer->name, '/') . '\s*<\/option>/',
        $response->getContent(),
        $matches
    );
    // Use Pest assertions
    expect($matches)->not->toBeEmpty(); // Pest
    // OR
    // $this->assertNotEmpty($matches); // PHPUnit

    // Assert that the response contains the vehicle's model select dropdown
    // and that it has the correct model ID selected
    // Assert that the model select dropdown is rendered
    $response->assertSee('<select id="modelSelect" name="model_id">', false);
    // Use regex to confirm the correct model is selected in the correct option tag
    preg_match(
        '/<option\s+value="' . $firstVehicle->model_id . '"[^>]*selected[^>]*>\s*' . preg_quote($firstVehicle->model->name, '/') . '\s*<\/option>/',
        $response->getContent(),
        $matches
    );
    // Use Pest assertions
    expect($matches)->not->toBeEmpty(); // Pest
    // OR
    // $this->assertNotEmpty($matches); // PHPUnit

    // Assert that the response contains the vehicle's year select dropdown
    $response->assertSee('<select name="year">', false);
    // Use regex to confirm the correct year is selected
    preg_match(
        '/<option\s+value="' . preg_quote($firstVehicle->year, '/') . '"[^>]*selected[^>]*>\s*' . preg_quote($firstVehicle->year, '/') . '\s*<\/option>/',
        $response->getContent(),
        $matches
    );
    // Pest
    expect($matches)->not->toBeEmpty();
    // PHPUnit alternative:
    // $this->assertNotEmpty($matches);

    // Assert that the response contains the vehicle's type radio button
    // and that at least one exists
    // Use regex to confirm the correct vehicle type radio button is checked
    preg_match(
        '/<label[^>]*>\s*<input\s+[^>]*name="vehicle_type_id"[^>]*value="' . preg_quote($firstVehicle->vehicle_type_id, '/') . '"[^>]*checked[^>]*>\s*' . preg_quote($firstVehicle->vehicleType->name, '/') . '\s*<\/label>/i',
        $response->getContent(),
        $matches
    );
    // Pest
    expect($matches)->not->toBeEmpty();
    // PHPUnit alternative:
    // $this->assertNotEmpty($matches);

    // Assert that the response contains the vehicle's price
    $response->assertSeeInOrder([
        'name="price"',
        ' value="' . $firstVehicle->price . '"',
    ], false);

    // Assert that the response contains the vehicle's VIN
    $response->assertSeeInOrder([
        'name="vin"',
        ' value="' . $firstVehicle->vin . '"',
    ], false);

    // Assert that the response contains the vehicle's mileage
    $response->assertSeeInOrder([
        'name="mileage"',
        ' value="' . $firstVehicle->mileage . '"',
    ], false);

    // Assert that the response contains the vehicle's fuel type radio button
    // and that at least one exists
    // Use regex to confirm the correct fuel type radio button is checked
    preg_match(
        '/<label[^>]*>\s*<input\s+[^>]*name="fuel_type_id"[^>]*value="' . preg_quote($firstVehicle->fuel_type_id, '/') . '"[^>]*checked[^>]*>\s*' . preg_quote($firstVehicle->fuelType->name, '/') . '\s*<\/label>/i',
        $response->getContent(),
        $matches
    );
    // Pest
    expect($matches)->not->toBeEmpty();
    // PHPUnit alternative:
    // $this->assertNotEmpty($matches);

    // Assert that the response contains the vehicle's province select dropdown
    // and that it has the correct province ID selected
    // Assert that the model select dropdown is rendered
    $response->assertSee('<select id="provinceSelect" name="province_id">', false);
    // Use regex to confirm the correct province is selected in the correct option tag
    preg_match(
        '/<option\s+value="' . $firstVehicle->city->province_id . '"\s+selected[^>]*>\s*' . preg_quote($firstVehicle->city->province->name, '/') . '\s*<\/option>/',
        $response->getContent(),
        $matches
    );
    // Use Pest assertions
    expect($matches)->not->toBeEmpty(); // Pest
    // OR
    // $this->assertNotEmpty($matches); // PHPUnit

    // Assert that the response contains the vehicle's city select dropdown
    // and that it has the correct city ID selected
    // Assert that the city dropdown is rendered
    $response->assertSee('<select id="citySelect" name="city_id">', false);
    // Confirm the correct city option is selected and structured properly
    preg_match(
        '/<option[^>]*value="' . $firstVehicle->city_id . '"[^>]*data-parent="' . $firstVehicle->city->province_id . '"[^>]*selected[^>]*>\s*' .
        preg_quote($firstVehicle->city->name, '/') .
        '\s*<\/option>/i',
        $response->getContent(),
        $matches
    );
    // Use Pest assertions
    expect($matches)->not->toBeEmpty(); // Pest
    // OR
    // $this->assertNotEmpty($matches); // PHPUnit

    // Assert that the response contains the vehicle's address
    $response->assertSeeInOrder([
        'name="address"',
        ' value="' . $firstVehicle->address . '"',
    ], false);

    // Assert that the response contains the vehicle's phone
    $response->assertSeeInOrder([
        'name="phone"',
        ' value="' . $firstVehicle->phone . '"',
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
    // Assert that the response contains the vehicle's features checkboxes
    // Loop through each feature key and check if the checkbox is present
    foreach ($featureKeys as $key) {
        $featureName = 'name="features[' . $key . ']"';
        $featureValue = 'value="1"';
        // If the feature is present in the vehicle's features
        if (!empty($firstVehicle->features[$key])) {
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

    // Assert that the response contains the vehicle's description textarea
    // Assert that the label is present
    $response->assertSee('<label>Detailed Description</label>', false);
    // Assert that the textarea is present with the vehicle's description
    $response->assertSeeInOrder([
        '<textarea',
        'name="description">' . $firstVehicle->description . '</textarea>',
    ], false);

    // Assert that the response contains the vehicle's published_at label
    // Assert that the label is present
    $response->assertSee('<label>Publish Date</label>', false);

    // dd([
    //     '$firstVehicle->published_at = ' . $firstVehicle->published_at,
    //     '$firstVehicle->published_at->format("Y-m-d") = ' . $firstVehicle->published_at->format('Y-m-d'),
    // ]);

    // Assert that the response contains the vehicle's published_at input
    $response->assertSeeInOrder([
        '<input',
        'type="date"',
        'name="published_at"',
        'value="' . optional($firstVehicle->published_at)->format('Y-m-d') . '"',
    ], false);
});

// Test for successfully updating vehicle details
it('should successfully update vehicle details', function () {
    // Seed the database with necessary data
    $this->seed();

    // Count the number of vehicles in the database before the test
    // This is useful to verify that no new vehicle is created after the test
    // and that the existing vehicle is updated
    $countVehicles = Vehicle::count();

    // Create a user to associate with the vehicle
    // This is necessary because the vehicle update form requires a user ID
    // and the user must be authenticated to update a vehicle
    // If the user is not authenticated, the request will fail
    // due to missing user ID in the request
    $user = User::first();

    // dd($user)

    // Select the first vehicle associated with the user
    // This is necessary to ensure that the vehicle being updated belongs to the user
    // If the user does not have any vehicles, this will return null
    // and the test will fail
    $firstVehicle = $user->vehicles()->first();

    // dd($firstVehicle);

    $features = [
        'abs' => '1',
        'air_conditioning' => '1',
        'power_windows' => '1',
        'power_door_locks' => '1',
        'cruise_control' => '1',
        'bluetooth_connectivity' => '1',
    ];

    // Create the vehicle data to be submitted
    // This simulates filling out the vehicle creation form with valid data
    // The fields are set to values that meet the validation rules
    // This will help ensure that the vehicle is created successfully
    $vehicleData = [
        'manufacturer_id' => 1,
        'model_id' => 1,
        'year' => 2020,
        'price' => 10000,
        'vin' => '11111111111111111',
        'mileage' => 10000,
        'vehicle_type_id' => 1,
        'fuel_type_id' => 1,
        'province_id' => 1,
        'city_id' => 1,
        'address' => '123 Main Street',
        'phone' => '0123456789',
        'features' => $features,
    ];

    /** @var \Illuminate\Testing\TestResponse $response */
    // Make a PUT request to the vehicle store route with invalid data
    // This simulates submitting the form with invalid data
    // The fields are set to values that do not meet the validation rules
    // This will help ensure that the validation rules are working correctly
    // and that the application does not allow creating a vehicle with invalid data
    // The fields are set to values that are outside the acceptable range
    // or format, such as negative prices, invalid years, etc.
    $response = $this->actingAs($user)->put(route('vehicle.update', $firstVehicle), $vehicleData);

    // Debugging: Check the session data to see what was submitted
    // This can help identify what data was sent in the request
    // $response->ddSession();

    // Assert that the response has validation errors for the required fields
    $response->assertRedirectToRoute('vehicle.index')
        ->assertSessionHas('success');

    // Add the vehicle ID to the vehicle data
    // This is necessary to associate the vehicle data with the vehicle
    // The vehicle ID is used to link the vehicle data to the specific vehicle listing
    // This allows us to assert the vehicle data in the database later
    $vehicleData['id'] = $firstVehicle->id;

    // Add the vehicle ID to the features array
    // This is necessary to associate the features with the vehicle
    // The vehicle ID is used to link the features to the specific vehicle listing
    // This allows us to assert the vehicle features in the database later
    // This is important for ensuring that the vehicle features are correctly associated
    // with the vehicle being updated
    $features['vehicle_id'] = $firstVehicle->id;

    // Remove non-db fields before DB check
    unset($vehicleData['features'], $vehicleData['province_id']);

    // Assert that the vehicle was updated in the database
    // And that the count of vehicles in the database is still the same
    // This is important to ensure that no new vehicle was created
    // and that the existing vehicle was updated with the new data
    $this->assertDatabaseCount('vehicles', $countVehicles);

    // Assert that the vehicle features were created in the database
    $this->assertDatabaseCount('vehicle_features', $countVehicles);
    // Assert that the vehicle was created in the database with the correct values
    $this->assertDatabaseHas('vehicles', $vehicleData);
    // Assert that the vehicle features is equal to the number of vehicles
    $this->assertDatabaseCount('vehicle_features', $countVehicles);
    // Assert that the vehicle features were created in the database with the correct values
    $this->assertDatabaseHas('vehicle_features', $features);
});

// Test for successfully deleting a vehicle
it('should successfully delete a vehicle', function () {
    // Seed the database with necessary data
    $this->seed();

    // Count the number of vehicles in the database before the test
    // This is useful to verify that the vehicle is deleted after the test
    $countVehicles = Vehicle::count();

    // Select the first user from the database
    // This is necessary because the vehicle deletion requires a user ID
    // and the user must be authenticated to delete a vehicle
    // If the user is not authenticated, the request will fail
    // due to missing user ID in the request
    $user = User::first();

    // dd($user)

    // Select the first vehicle associated with the user
    // This is necessary to ensure that the vehicle being deleted belongs to the user
    // If the user does not have any vehicles, this will return null
    // and the test will fail
    $firstVehicle = $user->vehicles()->first();

    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->actingAs($user)
        ->delete(route('vehicle.destroy', $firstVehicle));

    $response->assertRedirectToRoute('vehicle.index')
        ->assertSessionHas('success');

    // Assert that the vehicle was deleted from the database
    // And that the count of vehicles in the database is now one less than before
    $this->assertDatabaseHas('vehicles', [
        'id' => $firstVehicle->id,
        'deleted_at' => now(),
    ]);
});

// Test for uploading more images to a vehicle
it('should upload more images on the vehicle', function () {
    // Seed the database with necessary data
    $this->seed();

    // Select the first user from the database
    $user = User::first();

    // Select the first vehicle associated with the user
    $firstVehicle = $user->vehicles()->first();

    $oldCount = $firstVehicle->images()->count();

    // Create fake images to upload
    // This simulates uploading images for the vehicle listing
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
        ->post(route('vehicle.addImages', $firstVehicle), [
            'images' => $images,
        ]);

    $response->assertRedirectToRoute('vehicle.images', $firstVehicle)
        ->assertSessionHas('success');

    $newCount = $firstVehicle->images()->count();

    $this->assertEquals($newCount, $oldCount + count($images));
});

// Test for successfully deleting images on the vehicle
it('should successfully delete images on the vehicle', function () {
    // Seed the database with necessary data
    $this->seed();

    // Select the first user from the database
    $user = User::first();

    // Select the first vehicle associated with the user
    $firstVehicle = $user->vehicles()->first();

    // Count the number of images associated with the vehicle before deletion
    $oldCount = $firstVehicle->images()->count();

    // Get the IDs of the first two images associated with the vehicle
    $ids = $firstVehicle->images()->limit(2)->pluck('id')->toArray();

    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->actingAs($user)
        ->put(route('vehicle.updateImages', $firstVehicle), [
            'delete_images' => $ids,
        ]);

    // Assert that the response is a redirect
    // This indicates that the images were successfully deleted
    // and the user is redirected to the vehicle images route
    $response->assertStatus(302);

    // Count the number of images associated with the vehicle after deletion
    $newCount = $firstVehicle->images()->count();

    // Assert that the number of images after deletion is equal to the
    // old count minus the number of deleted images
    $this->assertEquals($newCount, $oldCount - 2);

    // Assert that the response redirects to the vehicle images route with
    // the first vehicle
    $response->assertRedirectToRoute('vehicle.images', $firstVehicle)
        ->assertSessionHas('success');

    // Assert that the number of images in the database is equal to
    // the new count
    $newCount = $firstVehicle->images()->count();

    // Assert that the number of images after deletion is equal to
    // the old count minus the number of deleted images
    $this->assertEquals($newCount, $oldCount - 2);
});

// Test for successfully updating image positions on the vehicle
it('should successfully update image positions', function () {
    // Seed the database with necessary data
    $this->seed();

    // Select the first user from the database
    $user = User::first();

    // Select the first vehicle associated with the user
    $firstVehicle = $user->vehicles()->first();

    // Get all the images associated with the vehicle
    // and reorder them by position in descending order
    // This is useful to ensure that the images are in the correct order
    // before updating their positions
    $images = $firstVehicle->images()->reorder('position', 'desc')->get();

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

    // Make a PUT request to the vehicle update images route with the new positions
    // This simulates submitting the form with the new image positions
    // The positions are set to the values in the $data array
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->actingAs($user)
        ->put(route('vehicle.updateImages', $firstVehicle), [
            'positions' => $data,
        ]);

    // Assert that the response is a redirect
    // This indicates that the image positions were successfully updated
    // and the user is redirected to the vehicle images route
    // This is important to ensure that the user is informed of the successful update
    $response->assertRedirectToRoute('vehicle.images', $firstVehicle)
        ->assertSessionHas('success');

    // Assert that the database has the updated image positions
    // This checks that each image ID in the $data array has the correct position
    foreach ($data as $id => $position) {
        $this->assertDatabaseHas('vehicle_images', [
            'id' => $id,
            'position' => $position,
        ]);
    }
});

// Test for ensuring that a user cannot access other users' vehicles
it('should test that the user can\'t access other users\' vehicles', function () {
    // Seed the database with necessary data
    $this->seed();

    // Select two users from the database
    // This is necessary to ensure that we have two different users
    // to test the access control
    [$user1, $user2] = User::limit(2)->get();

    // Debugging: Check the users to see their details
    // This can help identify if the users are correctly selected
    // dump($user1, $user2);

    // Select the first vehicle associated with user1
    $vehicle = $user1->vehicles()->first();

    // Act as user2 and try to access user1's vehicle
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->actingAs($user2)
        ->get(route('vehicle.edit', $vehicle));

    // Assert that the response is a 404 Not Found status
    $response->assertStatus(404);
});
