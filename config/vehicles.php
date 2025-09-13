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
            'long_name' => 'Motorcycles & Powersports',
            'description' => 'Two-wheel freedom for speed, style, and adventure.',
            'image_path' => 'https://images.unsplash.com/photo-1609630875171-b1321377ee65?q=80&w=680&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'vehicle_types' => ['Standard', 'Cruiser', 'Touring', 'Sport', 'Off-road', 'Dual-purpose'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Cars' => [
            'long_name' => 'Passenger Cars',
            'description' => 'Four-wheeled vehicles for personal and family use.',
            'image_path' => 'https://images.unsplash.com/photo-1702141583381-68d8b34a2898?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'vehicle_types' => ['Sedan', 'Hatchback', 'SUV', 'Bakkie', 'Minivan', '4X4'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Bakkies' => [
            'long_name' => 'Bakkies & LDVs',
            'description' => 'Versatile vehicles for both work and play.',
            'image_path' => 'https://images.pexels.com/photos/8438569/pexels-photo-8438569.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Single Cab', 'Double Cab', 'King Cab', 'Chassis Cab', 'Tow Truck', 'Service Body'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Minibuses' => [
            'long_name' => 'Minibuses & Transporters',
            'description' => 'Spacious vehicles for group travel and transport.',
            'image_path' => 'https://images.pexels.com/photos/5836331/pexels-photo-5836331.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Standard', 'High Roof', 'Luxury', 'Cargo', 'Passenger'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Vans' => [
            'long_name' => 'Light Commercial Vans',
            'description' => 'Practical vehicles for business and cargo needs.',
            'image_path' => 'https://images.pexels.com/photos/7763831/pexels-photo-7763831.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Cargo Van', 'Passenger Van', 'Crew Van', 'Camper Van'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Trucks' => [
            'long_name' => 'Heavy Commercial Vehicles',
            'description' => 'Trucks, busses and lorries for heavy-duty tasks.',
            'image_path' => 'https://images.pexels.com/photos/18982322/pexels-photo-18982322/free-photo-of-golden-renault-trucks-t.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Truck', 'Lorry', 'Bus', 'Tractor', 'Trailer', 'Tipper'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Towed' => [
            'long_name' => 'Towed Trailers & Caravans',
            'description' => 'Versatile vehicles for both work and play.',
            'image_path' => 'https://images.pexels.com/photos/19170449/pexels-photo-19170449/free-photo-of-trailer-parked-in-yard.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Single Axle', 'Double Axle', 'Gooseneck', 'Fifth Wheel', 'Toy Hauler', 'Pop-up'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Motorhomes' => [
            'long_name' => 'Motorhomes & RVs',
            'description' => 'Comfortable vehicles for long-distance travel.',
            'image_path' => 'https://images.unsplash.com/photo-1712765124506-67e68c30e90f?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTB8fG9yYW5nZSUyMG1vdG9yaG9tZXxlbnwwfHwwfHx8MA%3D%3D',
            'vehicle_types' => ['Single Cab', 'Double Cab', 'King Cab', 'Chassis Cab', 'Tow Truck', 'Service Body'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Civils' => [
            'long_name' => 'Civils & Construction',
            'description' => 'Versatile vehicles for both work and play.',
            'image_path' => 'https://images.pexels.com/photos/14704776/pexels-photo-14704776.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Excavator', 'Bulldozer', 'Backhoe', 'Dump Truck', 'Crane', 'Forklift'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Plant' => [
            'long_name' => 'Plant & Machinery',
            'description' => 'Plant and machinery for various industrial applications.',
            'image_path' => 'https://images.pexels.com/photos/29480712/pexels-photo-29480712/free-photo-of-ace-diesel-forklift-at-indian-manufacturing-facility.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Excavator', 'Bulldozer', 'Backhoe', 'Dump Truck', 'Crane', 'Forklift'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Agricultural' => [
            'long_name' => 'Agricultural Vehicles',
            'description' => 'Robust vehicles designed for farming and agricultural tasks.',
            'image_path' => 'https://images.pexels.com/photos/32958981/pexels-photo-32958981/free-photo-of-high-powered-ace-tractor-in-faridabad-india.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Tractor', 'Combine Harvester', 'Plough', 'Seeder', 'Baler', 'Sprayer'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Watercrafts' => [
            'long_name' => 'Boats & Watersports',
            'description' => 'Versatile vehicles for both work and play.',
            'image_path' => 'https://plus.unsplash.com/premium_photo-1677327623679-a3d951d52111?q=80&w=688&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'vehicle_types' => ['Fishing Boat', 'Sailboat', 'Yacht', 'Pontoon Boat', 'Jet Ski', 'Rubber Duck'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Aircraft' => [
            'long_name' => 'Planes and Aviation',
            'description' => 'Aircraft designed for various purposes.',
            'image_path' => 'https://images.pexels.com/photos/17485431/pexels-photo-17485431/free-photo-of-light-aircraft-on-field.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Single Engine', 'Multi Engine', 'Helicopter', 'Glider', 'Seaplane', 'Jet'],
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

// Category is attached to vehicles in the vehicles table
// Types are specific to each category
// Features can be common or specific to each category
