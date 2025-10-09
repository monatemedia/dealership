<?php // config/vehicles.php

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
            'singular' => 'Motorcycle',
            'long_name' => 'Motorcycles & Powersports',
            'description' => 'Two-wheel freedom for speed, style, and adventure.',
            'image_path' => 'https://images.unsplash.com/photo-1609630875171-b1321377ee65?q=80&w=680&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'vehicle_types' => ['Standard', 'Cruiser', 'Touring', 'Sport', 'Off-road', 'Dual-purpose'],
            'fuel_types' => ['Gasoline', 'Electric', 'Hybrid', 'Diesel'],
        ],
        'Cars' => [
            'singular' => 'Car',
            'long_name' => 'Passenger Cars',
            'description' => 'Vehicles for personal and family use.',
            'image_path' => 'https://images.unsplash.com/photo-1702141583381-68d8b34a2898?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'vehicle_types' => ['Sedan', 'Hatchback', 'SUV', 'Bakkie', 'Minivan', '4X4'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Bakkies' => [
            'singular' => 'Bakkie',
            'long_name' => 'Bakkies',
            'description' => 'Versatile bakkies for both work and play.',
            'image_path' => 'https://images.pexels.com/photos/8438569/pexels-photo-8438569.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Single Cab', 'Double Cab', 'King Cab', 'Chassis Cab', 'Tow Truck', 'Service Body'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Personal Trailers' => [
            'singular' => 'Personal Trailer',
            'long_name' => 'Personal Trailers & Caravans',
            'description' => 'Versatile vehicles for both work and play.',
            'image_path' => 'https://images.pexels.com/photos/19170449/pexels-photo-19170449/free-photo-of-trailer-parked-in-yard.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Single Axle', 'Double Axle', 'Gooseneck', 'Fifth Wheel', 'Toy Hauler', 'Pop-up'],
            'fuel_types' => [],
        ],
        'Motor Homes' => [
            'singular' => 'Motor Home',
            'long_name' => 'Motorhomes & Powered RVs',
            'description' => 'Comfortable vehicles for long-distance travel.',
            'image_path' => 'https://images.unsplash.com/photo-1712765124506-67e68c30e90f?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTB8fG9yYW5nZSUyMG1vdG9yaG9tZXxlbnwwfHwwfHx8MA%3D%3D',
            'vehicle_types' => ['Class A', 'Class B', 'Class C', 'Fifth Wheel', 'Travel Trailer', 'Pop-up Camper'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Watercraft' => [
            'singular' => 'Watercraft',
            'long_name' => 'Boats & Jetskis',
            'description' => 'Versatile vehicles for both work and play.',
            'image_path' => 'https://images.unsplash.com/photo-1622037764752-6cda75f99a9f?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'vehicle_types' => ['Fishing Boat', 'Sailboat', 'Yacht', 'Pontoon Boat', 'Jet Ski', 'Rubber Duck'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Minibuses' => [
            'singular' => 'Minibus',
            'long_name' => 'Minibuses and Taxi Vans',
            'description' => 'Spacious vehicles for group travel and transport.',
            'image_path' => 'https://c.anibis.ch/big/7596426995.jpg',
            'vehicle_types' => ['Standard', 'High Roof', 'Luxury', 'Cargo', 'Passenger'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Panel Vans' => [
            'singular' => 'Panel Van',
            'long_name' => 'Panel Vans',
            'description' => 'Practical vehicles for business and cargo needs.',
            'image_path' => 'https://images.pexels.com/photos/7763831/pexels-photo-7763831.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Cargo Van', 'Passenger Van', 'Crew Van', 'Camper Van'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Buses' => [
            'singular' => 'Bus',
            'long_name' => 'Bus & Coach',
            'description' => 'Buses for heavy-duty tasks.',
            'image_path' => 'https://images.unsplash.com/photo-1731448591600-22bbcb360f69?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'vehicle_types' => ['Bus', 'Coach', 'Double Decker', 'Shuttle Bus', 'School Bus'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Rigid Trucks' => [
            'singular' => 'Rigid Truck',
            'long_name' => 'Rigid Trucks',
            'description' => 'Heavy-duty vehicles for transporting goods.',
            'image_path' => 'https://images.pexels.com/photos/9280464/pexels-photo-9280464.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Flatbed', 'Box', 'Tanker', 'Reefer', 'Dump', 'Car Carrier'],
            'fuel_types' => ['Diesel', 'Electric', 'Hybrid'],
        ],
        'Truck Tractors' => [
            'singular' => 'Truck Tractor',
            'long_name' => 'Articulated Truck Tractors',
            'description' => 'Trucks and lorries for heavy-duty tasks.',
            'image_path' => 'https://images.pexels.com/photos/18982322/pexels-photo-18982322/free-photo-of-golden-renault-trucks-t.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Truck', 'Lorry', 'Bus', 'Tractor', 'Trailer', 'Tipper'],
            'fuel_types' => ['Diesel', 'Electric', 'Hybrid'],
        ],
        'Truck Trailers' => [
            'singular' => 'Truck Trailer',
            'long_name' => 'Articulated Truck Trailers',
            'description' => 'Trailers for heavy-duty tasks.',
            'image_path' => 'https://images.unsplash.com/photo-1712185908636-c5762a4f558a?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'vehicle_types' => ['Tautliner', 'Flatbed', 'Box Trailer', 'Refrigerated Trailer', 'Tanker', 'Lowboy', 'Lowbed', 'Flatdeck', 'Chassis', 'Car Carrier'],
            'fuel_types' => ['No Fuel', 'Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Plant Machinery' => [
            'singular' => 'Plant Machine',
            'long_name' => 'Plant Machinery',
            'description' => 'Plant and machinery for various industrial applications.',
            'image_path' => 'https://images.pexels.com/photos/14704776/pexels-photo-14704776.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Excavator', 'Backhoe', 'Loader', 'Roller', 'Crane', 'Scraper', 'Forklift', 'Grader', 'BOMAG Roller', 'Bulldozer', 'Compactor', 'Telehandler', ''],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Agri Plant Machinery' => [
            'singular' => 'Agri Machine',
            'long_name' => 'Agri Plant Machinery',
            'description' => 'Robust vehicles designed for farming and agricultural tasks.',
            'image_path' => 'https://images.pexels.com/photos/32958981/pexels-photo-32958981/free-photo-of-high-powered-ace-tractor-in-faridabad-india.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Tractor', 'Combine Harvester', 'Plough', 'Seeder', 'Baler', 'Sprayer'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Aircraft' => [
            'singular' => 'Aircraft',
            'long_name' => 'Planes and Aviation',
            'description' => 'Aircraft designed for various purposes.',
            'image_path' => 'https://images.pexels.com/photos/17485431/pexels-photo-17485431/free-photo-of-light-aircraft-on-field.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Single Engine', 'Multi Engine', 'Helicopter', 'Glider', 'Seaplane', 'Jet'],
            'fuel_types' => ['AvGas', 'Jet Fuel', 'Electric', 'Hybrid'],
        ],
    ],
];
