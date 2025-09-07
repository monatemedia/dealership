<?php

// config/vehicles.php

return [

    /*
    |--------------------------------------------------------------------------
    | Vehicle Categories and Body Types
    |--------------------------------------------------------------------------
    | A list of vehicle categories and their corresponding body types.
    |
    */
    'categories' => [
        'Motorcycles' => [
            'vehicle_types' => ['Standard', 'Cruiser', 'Touring', 'Sport', 'Off-road', 'Dual-purpose'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Cars' => [
            'vehicle_types' => ['Sedan', 'Hatchback', 'SUV', 'Bakkie', 'Minivan', '4X4'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Bakkies' => [
            'vehicle_types' => ['Single Cab', 'Double Cab', 'King Cab', 'Chassis Cab', 'Tow Truck', 'Service Body'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Vehicle Features
    |--------------------------------------------------------------------------
    | A list of common and category-specific features.
    |
    */
    'features' => [
        'common' => [
            'ABS',
            'Bluetooth Connectivity',
            'GPS Navigation',
        ],
        'Motorcycles' => [
            'Heated Grips',
            'Saddlebags',
            'Windshield',
        ],
        'Cars' => [
            'Air Conditioning',
            'Power Windows',
            'Power Door Locks',
            'Cruise Control',
            'Remote Start',
            'Heated Seats',
            'Climate Control',
            'Rear Parking Sensors',
            'Leather Seats',
        ],
        'Bakkies' => [
            'Tow Hitch',
            'Bed Liner',
            '4x4 Drivetrain',
            'Running Boards',
        ],
    ],
];

// Category is attached to cars in the cars table
// Types are specific to each category
// Features can be common or specific to each category
