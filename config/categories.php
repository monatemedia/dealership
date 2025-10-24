<?php // config/categories.php

return [

    /*
    |--------------------------------------------------------------------------
    | Vehicle Categories and Body Types
    |--------------------------------------------------------------------------
    | A list of vehicle categories and their corresponding body types.
    | NO LONGER IN USE
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

    /*
    |--------------------------------------------------------------------------
    | Vehicle Main Categories
    |--------------------------------------------------------------------------
    | A list of the main vehicle categories
    |
    */
    'main_categories' => [
        'Powersport' => [
            'singular' => 'Powersport',
            'long_name' => 'Powersport Vehicles',
            'description' => 'High-performance vehicles designed for speed and agility.',
            'image_path' => 'https://images.pexels.com/photos/33237473/pexels-photo-33237473/free-photo-of-orange-motocross-bike-under-tent-in-the-field.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'sub_categories' => [
                'Motorcycles',
                'ATVs',
                'Side by Sides',
            ],
        ],
        'Light Vehicles' => [
            'singular' => 'Light Vehicle',
            'long_name' => 'Light Vehicles',
            'description' => 'A wide range of vehicles for personal and commercial use.',
            'image_path' => 'https://images.pexels.com/photos/11095885/pexels-photo-11095885.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'sub_categories' => [
                'Cars',
                'Personal Trailers',
                'Caravans',
                'Motor Homes',
            ],
        ],
        'Light Commercials' => [
            'singular' => 'Light Commercial',
            'long_name' => 'Light Commercial Vehicles',
            'description' => 'Bakkies, Minibuses and Panel Vans.',
            'image_path' => 'https://images.pexels.com/photos/16058147/pexels-photo-16058147/free-photo-of-ford-ranger-pickup-truck.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'sub_categories' => [
                'Bakkies',
                'Minibuses',
                'Panel Vans',
            ],
        ],
        'Heavy Commercials' => [
            'singular' => 'Heavy Commercial',
            'long_name' => 'Buses, Trucks and Truck Trailers.',
            'description' => 'Vehicles designed for transporting goods and people.',
            'image_path' => 'https://images.pexels.com/photos/33095902/pexels-photo-33095902/free-photo-of-orange-truck-parked-by-industrial-building.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'sub_categories' => [
                'Buses',
                'Rigid Trucks',
                'Truck Tractors',
                'Truck Trailers'
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Vehicle Sub-categories
    |--------------------------------------------------------------------------
    | A list of vehicle sub-categories
    | Subcategory should be included in a main category
    |
    */
    'sub_categories' => [
        'Motorcycles' => [
            'singular' => 'Motorcycle',
            'long_name' => 'Motorcycles & Powersports',
            'description' => 'Two-wheel freedom for speed, style, and adventure.',
            'image_path' => 'https://images.unsplash.com/photo-1609630875171-b1321377ee65?q=80&w=680&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
        ],
        'ATVs' => [
            'singular' => 'ATV',
            'long_name' => 'All-Terrain Vehicles',
            'description' => 'Off-road vehicles for various terrains.',
            'image_path' => 'https://images.pexels.com/photos/26570063/pexels-photo-26570063/free-photo-of-quad-on-barren-desert-at-sunset.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
        ],
        'Side by Sides' => [
            'singular' => 'Side by Side',
            'long_name' => 'Side by Side Vehicles',
            'description' => 'Side by side vehicles for utility tasks.',
            'image_path' => 'https://images.pexels.com/photos/30932918/pexels-photo-30932918/free-photo-of-dynamic-orange-off-road-buggy-on-asphalt-track.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
        ],
        'Cars' => [
            'singular' => 'Car',
            'long_name' => 'Passenger Cars',
            'description' => 'Vehicles for personal and family use.',
            'image_path' => 'https://images.unsplash.com/photo-1702141583381-68d8b34a2898?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
        ],
        'Bakkies' => [
            'singular' => 'Bakkie',
            'long_name' => 'Bakkies',
            'description' => 'Versatile bakkies for both work and play.',
            'image_path' => 'https://images.pexels.com/photos/8438569/pexels-photo-8438569.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
        ],
        'Personal Trailers' => [
            'singular' => 'Personal Trailer',
            'long_name' => 'Personal Trailers & Caravans',
            'description' => 'Trailers for all your hauling needs.',
            'image_path' => 'https://images.unsplash.com/photo-1617085979589-a61ffc926e13?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
        ],
        'Caravans' => [
            'singular' => 'Caravan',
            'long_name' => 'Personal Trailers & Caravans',
            'description' => 'Versatile vehicles for both work and play.',
            'image_path' => 'https://images.pexels.com/photos/19170449/pexels-photo-19170449/free-photo-of-trailer-parked-in-yard.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
        ],
        'Motor Homes' => [
            'singular' => 'Motor Home',
            'long_name' => 'Motorhomes & Powered RVs',
            'description' => 'Comfortable vehicles for long-distance travel.',
            'image_path' => 'https://images.unsplash.com/photo-1712765124506-67e68c30e90f?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTB8fG9yYW5nZSUyMG1vdG9yaG9tZXxlbnwwfHwwfHx8MA%3D%3D',
        ],
        'Minibuses' => [
            'singular' => 'Minibus',
            'long_name' => 'Minibuses and Panel Vans',
            'description' => 'Spacious vehicles for group travel and transport.',
            'image_path' => 'https://c.anibis.ch/big/7596426995.jpg',
        ],
        'Panel Vans' => [
            'singular' => 'Panel Van',
            'long_name' => 'Panel Vans',
            'description' => 'Practical vehicles for business and cargo needs.',
            'image_path' => 'https://images.pexels.com/photos/7763831/pexels-photo-7763831.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
        ],
        'Buses' => [
            'singular' => 'Bus',
            'long_name' => 'Bus & Coach',
            'description' => 'Buses for heavy-duty tasks.',
            'image_path' => 'https://images.unsplash.com/photo-1731448591600-22bbcb360f69?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
        ],
        'Rigid Trucks' => [
            'singular' => 'Rigid Truck',
            'long_name' => 'Rigid Trucks',
            'description' => 'Heavy-duty vehicles for transporting goods.',
            'image_path' => 'https://images.pexels.com/photos/9280464/pexels-photo-9280464.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
        ],
        'Truck Tractors' => [
            'singular' => 'Truck Tractor',
            'long_name' => 'Articulated Truck Tractors',
            'description' => 'Trucks and lorries for heavy-duty tasks.',
            'image_path' => 'https://images.pexels.com/photos/18982322/pexels-photo-18982322/free-photo-of-golden-renault-trucks-t.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
        ],
        'Truck Trailers' => [
            'singular' => 'Truck Trailer',
            'long_name' => 'Articulated Truck Trailers',
            'description' => 'Trailers for heavy-duty tasks.',
            'image_path' => 'https://images.unsplash.com/photo-1712185908636-c5762a4f558a?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Vehicle Types by Category
    |--------------------------------------------------------------------------
    | A static list of vehicle types used across the application.
    |
    */
    'vehicle_types_by_category' => [
        'Motorcycles' => [
            [
                'name' => 'Standard',
                'long_name' => 'Standard Motorcycle',
                'description' => 'A standard motorcycle for everyday use.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Standard',
            ],
            [
                'name' => 'Cruiser',
                'long_name' => 'Cruiser Motorcycle',
                'description' => 'A motorcycle designed for long-distance cruising.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Cruiser',
            ],
            [
                'name' => 'Touring',
                'long_name' => 'Touring Motorcycle',
                'description' => 'A motorcycle built for long-distance travel.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Touring',
            ],
            [
                'name' => 'Sport',
                'long_name' => 'Sport Motorcycle',
                'description' => 'A high-performance motorcycle designed for speed and agility.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Sport',
            ],
            [
                'name' => 'Off-road',
                'long_name' => 'Off-road Motorcycle',
                'description' => 'A motorcycle designed for off-road riding.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Off-road',
            ],
            [
                'name' => 'Dual-purpose',
                'long_name' => 'Dual-purpose Motorcycle',
                'description' => 'A motorcycle designed for both on-road and off-road riding.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Dual-purpose',
            ],
        ],
        'ATVs' => [
            [
                'name' => 'Sport',
                'long_name' => 'Sport ATV',
                'description' => 'An ATV designed for high performance and agility.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Sport',
            ],
            [
                'name' => 'Utility',
                'long_name' => 'Utility ATV',
                'description' => 'An ATV designed for heavy-duty tasks and towing.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Utility',
            ],
            [
                'name' => 'Youth',
                'long_name' => 'Youth ATV',
                'description' => 'An ATV designed for younger riders.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Youth',
            ],
        ],
        'Side by Sides' => [
            [
                'name' => 'Recreational',
                'long_name' => 'Recreational Side by Side',
                'description' => 'A side by side designed for recreational use.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Recreational',
            ],
            [
                'name' => 'Utility',
                'long_name' => 'Utility Side by Side',
                'description' => 'A side by side designed for utility tasks.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Utility',
            ],
            [
                'name' => 'Sport',
                'long_name' => 'Sport Side by Side',
                'description' => 'A high-performance side by side designed for speed and agility.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Sport',
            ],
        ],
        'Cars' => [
            [
                'name' => 'Sedan',
                'long_name' => 'Sedan Car',
                'description' => 'A sedan car for everyday use.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Sedan',
            ],
            [
                'name' => 'Hatchback',
                'long_name' => 'Hatchback Car',
                'description' => 'A hatchback car for urban driving.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Hatchback',
            ],
            [
                'name' => 'SUV',
                'long_name' => 'SUV Car',
                'description' => 'A sport utility vehicle for all terrains.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=SUV',
            ],
            [
                'name' => 'Coupe',
                'long_name' => 'Coupe Car',
                'description' => 'A two-door car with a sporty design.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Coupe',
            ],
            [
                'name' => 'Crossover',
                'long_name' => 'Crossover Car',
                'description' => 'A vehicle that combines features of cars and SUVs.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Crossover',
            ],
            [
                'name' => 'Sports Vehicle',
                'long_name' => 'Sports Vehicle',
                'description' => 'A high-performance vehicle designed for speed.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Sports+Vehicle',
            ],
        ],
        'Bakkies' => [
            [
                'name' => 'Single Cab',
                'long_name' => 'Single Cab Bakkie',
                'description' => 'A single cab bakkie for light-duty tasks.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Single+Cab',
            ],
            [
                'name' => 'Double Cab',
                'long_name' => 'Double Cab Bakkie',
                'description' => 'A double cab bakkie for family and work.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Double+Cab',
            ],
            [
                'name' => 'King Cab',
                'long_name' => 'King Cab Bakkie',
                'description' => 'A king cab bakkie for extra space.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=King+Cab',
            ],
            [
                'name' => 'Chassis Cab',
                'long_name' => 'Chassis Cab Bakkie',
                'description' => 'A chassis cab bakkie for custom builds.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Chassis+Cab',
            ],
            [
                'name' => 'Tow Truck',
                'long_name' => 'Tow Truck Bakkie',
                'description' => 'A tow truck bakkie for hauling vehicles.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Tow+Truck',
            ],
            [
                'name' => 'Service Body',
                'long_name' => 'Service Body Bakkie',
                'description' => 'A service body bakkie for utility work.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Service+Body',
            ],
        ],
        'Personal Trailers' => [
            [
                'name' => 'Single Axle',
                'long_name' => 'Single Axle Trailer',
                'description' => 'A single axle trailer for light loads.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Single+Axle',
            ],
            [
                'name' => 'Double Axle',
                'long_name' => 'Double Axle Trailer',
                'description' => 'A double axle trailer for heavier loads.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Double+Axle',
            ],
            [
                'name' => 'Gooseneck',
                'long_name' => 'Gooseneck Trailer',
                'description' => 'A gooseneck trailer for increased stability.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Gooseneck',
            ],
            [
                'name' => 'Fifth Wheel',
                'long_name' => 'Fifth Wheel Trailer',
                'description' => 'A fifth wheel trailer for large loads.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Fifth+Wheel',
            ],
            [
                'name' => 'Toy Hauler',
                'long_name' => 'Toy Hauler Trailer',
                'description' => 'A toy hauler trailer for transporting recreational vehicles.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Toy+Hauler',
            ],
            [
                'name' => 'Pop-up',
                'long_name' => 'Pop-up Trailer',
                'description' => 'A pop-up trailer for easy storage and transport.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Pop-up',
            ],
        ],
        'Caravans' => [
            [
                'name' => 'Luxury',
                'long_name' => 'Luxury Caravan',
                'description' => 'A luxury caravan for comfortable travel.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Luxury',
            ],
            [
                'name' => 'Family',
                'long_name' => 'Family Caravan',
                'description' => 'A family caravan for group travel.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Family',
            ],
            [
                'name' => 'Off-road',
                'long_name' => 'Off-road Caravan',
                'description' => 'An off-road caravan for adventurous trips.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Off-road',
            ],
            [
                'name' => 'Compact',
                'long_name' => 'Compact Caravan',
                'description' => 'A compact caravan for easy maneuverability.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Compact',
            ],
            [
                'name' => 'Camper',
                'long_name' => 'Camper Caravan',
                'description' => 'A camper caravan for outdoor enthusiasts.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Camper',
            ],
            [
                'name' => 'Travel Trailer',
                'long_name' => 'Travel Trailer Caravan',
                'description' => 'A travel trailer caravan for road trips.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Travel+Trailer',
            ],
        ],
        'Motor Homes' => [
            [
                'name' => 'Class A',
                'long_name' => 'Class A Motor Home',
                'description' => 'A Class A motor home for luxury travel.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Class+A',
            ],
            [
                'name' => 'Class B',
                'long_name' => 'Class B Motor Home',
                'description' => 'A Class B motor home for compact travel.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Class+B',
            ],
            [
                'name' => 'Class C',
                'long_name' => 'Class C Motor Home',
                'description' => 'A Class C motor home for family travel.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Class+C',
            ],
            [
                'name' => 'Fifth Wheel',
                'long_name' => 'Fifth Wheel Motor Home',
                'description' => 'A fifth wheel motor home for spacious travel.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Fifth+Wheel',
            ],
            [
                'name' => 'Travel Trailer',
                'long_name' => 'Travel Trailer Motor Home',
                'description' => 'A travel trailer motor home for road trips.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Travel+Trailer',
            ],
            [
                'name' => 'Pop-up Camper',
                'long_name' => 'Pop-up Camper Motor Home',
                'description' => 'A pop-up camper motor home for easy storage.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Pop-up+Camper',
            ],
        ],
        'Minibuses' => [
            [
                'name' => 'Standard',
                'long_name' => 'Standard Minibus',
                'description' => 'A standard minibus for group travel.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Standard',
            ],
            [
                'name' => 'High Roof',
                'long_name' => 'High Roof Minibus',
                'description' => 'A high roof minibus for extra headroom.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=High+Roof',
            ],
            [
                'name' => 'Luxury',
                'long_name' => 'Luxury Minibus',
                'description' => 'A luxury minibus for premium travel.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Luxury',
            ],
        ],
        'Panel Vans' => [
            [
                'name' => 'Cargo Van',
                'long_name' => 'Cargo Panel Van',
                'description' => 'A cargo panel van for transporting goods.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Cargo+Van',
            ],
            [
                'name' => 'Passenger Van',
                'long_name' => 'Passenger Panel Van',
                'description' => 'A passenger panel van for group travel.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Passenger+Van',
            ],
            [
                'name' => 'Crew Van',
                'long_name' => 'Crew Panel Van',
                'description' => 'A crew panel van for work teams.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Crew+Van',
            ],
            [
                'name' => 'Camper Van',
                'long_name' => 'Camper Panel Van',
                'description' => 'A camper panel van for recreational travel.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Camper+Van',
            ],
        ],
        'Buses' => [
            [
                'name' => 'Bus',
                'long_name' => 'Standard Bus',
                'description' => 'A standard bus for public transport.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Bus',
            ],
            [
                'name' => 'Coach',
                'long_name' => 'Luxury Coach',
                'description' => 'A luxury coach for long-distance travel.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Coach',
            ],
            [
                'name' => 'Double Decker',
                'long_name' => 'Double Decker Bus',
                'description' => 'A double decker bus for high-capacity transport.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Double+Decker',
            ],
            [
                'name' => 'Shuttle Bus',
                'long_name' => 'Shuttle Bus',
                'description' => 'A shuttle bus for short-distance travel.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Shuttle+Bus',
            ],
            [
                'name' => 'School Bus',
                'long_name' => 'School Bus',
                'description' => 'A school bus for student transport.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=School+Bus',
            ],
            [
                'name' => 'Luxury Bus',
                'long_name' => 'Luxury Bus',
                'description' => 'A luxury bus for premium travel experiences.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Luxury+Bus',
            ],
        ],
        'Rigid Trucks' => [
            [
                'name' => 'Flatbed',
                'long_name' => 'Flatbed Rigid Truck',
                'description' => 'A flatbed rigid truck for transporting large items.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Flatbed',
            ],
            [
                'name' => 'Box',
                'long_name' => 'Box Rigid Truck',
                'description' => 'A box rigid truck for secure cargo transport.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Box',
            ],
            [
                'name' => 'Tanker',
                'long_name' => 'Tanker Rigid Truck',
                'description' => 'A tanker rigid truck for transporting liquids.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6d6/e9580c/svg?font=open-sans&text=Tanker',
            ],
            [
                'name' => 'Reefer',
                'long_name' => 'Reefer Rigid Truck',
                'description' => 'A refrigerated rigid truck for perishable goods.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6d6/e9580c/svg?font=open-sans&text=Reefer',
            ],
            [
                'name' => 'Dump',
                'long_name' => 'Dump Rigid Truck',
                'description' => 'A dump rigid truck for construction materials.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6d6/e9580c/svg?font=open-sans&text=Dump',
            ],
        ],
        'Truck Tractors' => [
            [
                'name' => 'Sleeper Cab',
                'long_name' => 'Sleeper Cab Truck Tractor',
                'description' => 'A truck tractor with a sleeper cab for long hauls.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Sleeper+Cab',
            ],
            [
                'name' => 'Day Cab',
                'long_name' => 'Day Cab Truck Tractor',
                'description' => 'A truck tractor with a day cab for short hauls.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Day+Cab',
            ],
            [
                'name' => 'Conventional',
                'long_name' => 'Conventional Truck Tractor',
                'description' => 'A conventional truck tractor for various hauling needs.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Conventional',
            ],
            [
                'name' => 'Cab Over',
                'long_name' => 'Cab Over Truck Tractor',
                'description' => 'A cab over truck tractor for better maneuverability.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Cab+Over',
            ],
            [
                'name' => 'Flat Nose',
                'long_name' => 'Flat Nose Truck Tractor',
                'description' => 'A flat nose truck tractor for urban deliveries.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Flat+Nose',
            ],
            [
                'name' => 'Long Nose',
                'long_name' => 'Long Nose Truck Tractor',
                'description' => 'A long nose truck tractor for heavy-duty hauling.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Long+Nose',
            ],
        ],
        'Truck Trailers' => [
            [
                'name' => 'Tautliner',
                'description' => 'A tautliner trailer for flexible loading and unloading.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Tautliner',
            ],
            [
                'name' => 'Flatbed',
                'description' => 'A flatbed trailer for easy loading of heavy goods.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Flatbed',
            ],
            [
                'name' => 'Box Trailer',
                'description' => 'A box trailer for secure transport of goods.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Box+Trailer',
            ],
            [
                'name' => 'Refrigerated Trailer',
                'description' => 'A refrigerated trailer for perishable goods.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Refrigerated+Trailer',
            ],
            [
                'name' => 'Tanker',
                'description' => 'A tanker trailer for transporting liquids.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Tanker',
            ],
            [
                'name' => 'Lowboy',
                'description' => 'A lowboy trailer for heavy equipment transport.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Lowboy',
            ],
            [
                'name' => 'Lowbed',
                'description' => 'A lowbed trailer for oversized loads.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Lowbed',
            ],
            [
                'name' => 'Flatdeck',
                'description' => 'A flatdeck trailer for versatile cargo transport.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Flatdeck',
            ],
            [
                'name' => 'Chassis',
                'description' => 'A chassis trailer for container transport.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Chassis',
            ],
            [
                'name' => 'Car Carrier',
                'description' => 'A car carrier trailer for vehicle transport.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Car+Carrier',
            ],
            [
                'name' => 'Container',
                'description' => 'A container trailer for intermodal transport.',
                'image_path' => 'https://placehold.co/600x400/d6d6d6/e9580c/svg?font=open-sans&text=Container',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Fuel Types by Category
    |--------------------------------------------------------------------------
    | Maps sub-categories to their allowed fuel type groups from config/lookups.php
    | Each sub-category includes:
    | - 'groups': Array of fuel type group names from lookups.fuel_types
    | - 'default': Default fuel type value for the category
    | - 'can_edit': Boolean indicating if the user can change the fuel type
    |
    */
    'fuel_types_by_category' => [
        // Powersport vehicles - only Powersport fuel types
        'Motorcycles' => [
            'groups' => ['Powersport'],
            'default' => 'Petrol',
            'can_edit' => true,
        ],
        'ATVs' => [
            'groups' => ['Powersport'],
            'default' => 'Petrol',
            'can_edit' => true,
        ],
        'Side by Sides' => [
            'groups' => ['Powersport'],
            'default' => 'Petrol',
            'can_edit' => true,
        ],

        // Non-powered vehicles - no fuel types
        'Personal Trailers' => [
            'groups' => ['None'],
            'default' => 'None',
            'can_edit' => false,
        ],
        'Caravans' => [
            'groups' => ['None'],
            'default' => 'None',
            'can_edit' => false,
        ],

        // Passenger vehicles - Petrol default
        'Cars' => [
            'groups' => ['Internal Combustion', 'Electric', 'High Pressure Gas', 'None'],
            'can_edit' => true,
        ],
        'Motor Homes' => [
            'groups' => ['Internal Combustion', 'Electric', 'High Pressure Gas', 'None'],
            'can_edit' => true,
        ],

        // Commercial vehicles - Diesel default
        'Bakkies' => [
            'groups' => ['Internal Combustion', 'Electric', 'High Pressure Gas', 'None'],
            'can_edit' => true,
        ],
        'Minibuses' => [
            'groups' => ['Internal Combustion', 'Electric', 'High Pressure Gas', 'None'],
            'can_edit' => true,
        ],
        'Panel Vans' => [
            'groups' => ['Internal Combustion', 'Electric', 'High Pressure Gas', 'None'],
            'can_edit' => true,
        ],
        'Buses' => [
            'groups' => ['Internal Combustion', 'Electric', 'High Pressure Gas', 'None'],
            'default' => 'Diesel',
            'can_edit' => true,
        ],
        'Rigid Trucks' => [
            'groups' => ['Internal Combustion', 'Electric', 'High Pressure Gas', 'None'],
            'default' => 'Diesel',
            'can_edit' => true,
        ],
        'Truck Tractors' => [
            'groups' => ['Internal Combustion', 'Electric', 'High Pressure Gas', 'None'],
            'default' => 'Diesel',
            'can_edit' => true,
        ],

        // Truck trailers - None default
        'Truck Trailers' => [
            'groups' => ['Internal Combustion', 'Electric', 'High Pressure Gas', 'None'],
            'default' => 'None',
            'can_edit' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Transmissions by Category
    |--------------------------------------------------------------------------
    | Maps sub-categories to their allowed transmission groups.
    */
    'transmissions_by_category' => [
        // Powersport
        'Motorcycles' => [
            'groups' => ['Manual', 'Advanced', 'None'],
            'can_edit' => true,
        ],
        'ATVs' => [
            'groups' => ['Manual', 'Automatic', 'Advanced', 'None'],
            'can_edit' => true,
        ],
        'Side by Sides' => [
            'groups' => ['Advanced'],
            'default' => 'Continuously Variable Transmission (CVT)',
            'can_edit' => true,
        ],
        // Non-powered
        'Personal Trailers' => [
            'groups' => ['None'],
            'default' => 'None / Not Specified',
            'can_edit' => false,
        ],
        'Caravans' => [
            'groups' => ['None'],
            'default' => 'None / Not Specified',
            'can_edit' => false,
        ],
        // Passenger
        'Cars' => [
            'groups' => ['Manual', 'Automatic', 'Advanced', 'None'],
            'can_edit' => true,
        ],
        'Motor Homes' => [
            'groups' => ['Automatic', 'Manual', 'None'],
            'default' => '6-Speed Automatic',
            'can_edit' => true,
        ],
        // Commercial
        'Bakkies' => [
            'groups' => ['Manual', 'Automatic', 'Advanced', 'None'],
            'can_edit' => true,
        ],
        'Minibuses' => [
            'groups' => ['Manual', 'Automatic', 'None'],
            'can_edit' => true,
        ],
        'Panel Vans' => [
            'groups' => ['Manual', 'Automatic', 'None'],
            'can_edit' => true,
        ],
        'Buses' => [
            'groups' => ['Automatic', 'Truck Manual', 'None'],
            'can_edit' => true,
        ],
        'Rigid Trucks' => [
            'groups' => ['Truck Manual', 'Automatic', 'Advanced', 'None'],
            'can_edit' => true,
        ],
        'Truck Tractors' => [
            'groups' => ['Truck Manual', 'Automatic', 'Advanced', 'None'],
            'can_edit' => true,
        ],
        'Truck Trailers' => [
            'groups' => ['None'],
            'default' => 'None / Not Specified',
            'can_edit' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Drivetrain by Category
    |--------------------------------------------------------------------------
    | Maps sub-categories to their allowed drive train groups.
    */
    'drivetrain_by_category' => [
        // Powersport
        'Motorcycles' => [
            'groups' => ['Standard'],
            'default' => 'Rear-Wheel Drive (RWD)',
            'can_edit' => true,
        ],
        'ATVs' => [
            'groups' => ['Standard', 'Advanced'],
            'can_edit' => true,
        ],
        'Side by Sides' => [
            'groups' => ['Standard', 'Advanced'],
            'can_edit' => true,
        ],
        // Non-powered
        'Personal Trailers' => [
            'groups' => ['None'],
            'default' => 'None / Not Specified',
            'can_edit' => false,
        ],
        'Caravans' => [
            'groups' => ['None'],
            'default' => 'None / Not Specified',
            'can_edit' => false,
        ],
        // Passenger
        'Cars' => [
            'groups' => ['Standard', 'Advanced', 'None'],
            'can_edit' => true,
        ],
        'Motor Homes' => [
            'groups' => ['Standard', 'Advanced', 'None'],
            'default' => 'Rear-Wheel Drive (RWD)',
            'can_edit' => true,
        ],
        // Commercial
        'Bakkies' => [
            'groups' => ['Standard', 'Advanced', 'None'],
            'can_edit' => true,
        ],
        'Minibuses' => [
            'groups' => ['Standard', 'None'],
            'can_edit' => true,
        ],
        'Panel Vans' => [
            'groups' => ['Standard', 'None'],
            'can_edit' => true,
        ],
        'Buses' => [
            'groups' => ['Standard', 'Advanced', 'Special', 'None'],
            'default' => 'Rear-Wheel Drive (RWD)',
            'can_edit' => true,
        ],
        'Rigid Trucks' => [
            'groups' => ['Standard', 'Advanced', 'Special', 'None'],
            'can_edit' => true,
        ],
        'Truck Tractors' => [
            'groups' => ['Standard', 'Advanced', 'Special', 'None'],
            'can_edit' => true,
        ],
        'Truck Trailers' => [
            'groups' => ['None'],
            'default' => 'None / Not Specified',
            'can_edit' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Features by Category
    |--------------------------------------------------------------------------
    | Maps sub-categories to their allowed feature groups.
    | This is simpler because features are multi-select (checkboxes).
    */
    'features_by_category' => [
        'Motorcycles' => ['Comfort and Convenience', 'Safety Features', 'Technology'],
        'ATVs' => ['Comfort and Convenience', 'Safety Features', 'Technology', 'Modifications (Trucks)'],
        'Side by Sides' => ['Comfort and Convenience', 'Safety Features', 'Technology', 'Modifications (Trucks)'],
        'Personal Trailers' => ['Comfort and Convenience', 'Technology'],
        'Caravans' => ['Comfort and Convenience', 'Safety Features', 'Technology'],
        'Cars' => ['Comfort and Convenience', 'Safety Features', 'Technology'],
        'Motor Homes' => ['Comfort and Convenience', 'Safety Features', 'Technology'],
        'Bakkies' => ['Comfort and Convenience', 'Safety Features', 'Technology', 'Modifications (Trucks)'],
        'Minibuses' => ['Comfort and Convenience', 'Safety Features', 'Technology'],
        'Panel Vans' => ['Comfort and Convenience', 'Safety Features', 'Technology'],
        'Buses' => ['Comfort and Convenience', 'Safety Features', 'Technology', 'Modifications (Trucks)'],
        'Rigid Trucks' => ['Comfort and Convenience', 'Safety Features', 'Technology', 'Modifications (Trucks)'],
        'Truck Tractors' => ['Comfort and Convenience', 'Safety Features', 'Technology', 'Modifications (Trucks)'],
        'Truck Trailers' => ['Modifications (Trucks)'],
    ],
];
