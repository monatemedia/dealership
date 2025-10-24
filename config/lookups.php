<?php // config/lookups.php

return [

    /*
    |--------------------------------------------------------------------------
    | Vehicle Main Categories
    |--------------------------------------------------------------------------
    | A list of the main vehicle categories
    | NO LONGER IN USE
    |
    */
    'main_categories' => [
        'Powersport' => [
            'singular' => 'Powersport',
            'long_name' => 'Powersport Vehicles',
            'description' => 'High-performance vehicles designed for speed and agility.',
            'image_path' => 'https://images.unsplash.com/photo-1609630875171-b1321377ee65?q=80&w=680&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'sub_categories' => [
                'Motorcycles',
                'ATVs',
                'UTVs',
                // 'Snowmobiles'
            ],
        ],
        'Automotive' => [
            'singular' => 'Automotive',
            'long_name' => 'Automotive Vehicles',
            'description' => 'A wide range of vehicles for personal and commercial use.',
            'image_path' => 'https://images.unsplash.com/photo-1704325053140-bffd91ea4861?q=80&w=1332&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'sub_categories' => ['Cars', 'Bakkies', 'Personal Trailers', 'Caravans', 'Motor Homes'],
        ],
        'Transportors' => [
            'singular' => 'Transportor',
            'long_name' => 'Transportor Vehicles',
            'description' => 'Vehicles designed for transporting goods and people.',
            'image_path' => 'https://images.unsplash.com/photo-1712185908636-c5762a4f558a?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'sub_categories' => ['Minibuses', 'Panel Vans', 'Buses', 'Rigid Trucks', 'Truck Tractors', 'Truck Trailers'],
        ],
        'Pleasure Marine' => [
            'singular' => 'Pleasure Marine',
            'long_name' => 'Pleasure Marine Vehicles',
            'description' => 'Boats and ships for water travel.',
            'image_path' => 'https://images.unsplash.com/photo-1662657736642-83b53193d7f1?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'sub_categories' => ['Personal Watercraft', 'Motorboats', 'Sailboats', ],
        ],
        'Plant Machinery' => [
            'singular' => 'Plant Machinery',
            'long_name' => 'Plant Machinery Vehicles',
            'description' => 'Heavy-duty vehicles for various industrial applications.',
            'image_path' => 'https://images.pexels.com/photos/14704776/pexels-photo-14704776.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            // See: https://www.jungheinrich.co.za/products/forklift-knowledge-bank/which-type-of-forklift-fits-which-transport-task-776314#:~:text=Electric%20Tow%20Tractors.%20Tractors%20are%20designed%20to,trailers%20or%20in%20roll%20containers%2C%20for%20example.
            'sub_categories' => ['Pedestrian Pallet Trucks', 'Stackers', 'Forklifts', 'Reach Trucks', 'Order Pickers', 'Electric Tow Tractors', 'Narrow Aisle Trucks', 'Scissor Lifts', 'Other'],
        ],
        'Agri Machinery' => [
            'singular' => 'Agri Machinery',
            'long_name' => 'Agricultural Machinery Vehicles',
            'description' => 'Robust vehicles designed for farming and agricultural tasks.',
            'image_path' => 'https://images.pexels.com/photos/32958981/pexels-photo-32958981/free-photo-of-high-powered-ace-tractor-in-faridabad-india.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'sub_categories' => ['Farm Tractors', 'Combines', 'Ploughs', 'Seeders', 'Sprayers', 'Balers', 'Cultivators', 'Grain Carts'],
        ],
        'Yellow Metal' => [
            'singular' => 'Yellow Metal',
            'long_name' => 'Yellow Metal Vehicles',
            'description' => 'Robust vehicles designed for construction and industrial tasks.',
            'image_path' => 'https://images.pexels.com/photos/14704776/pexels-photo-14704776.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            // Applications: Digging, Loading, Material Handling, Earthmoving, Roadwork, Drilling, Mining, Demolition,
            'sub_categories' => ['Bulldozers', 'Snowcats',  'Industrial Tractors', 'Motor Graders', 'Excavators', 'Compact Excavators', 'Asphalt Pavers', 'Backhoe Loaders', 'Cold Planers', 'Compactors', 'Pipelayers', 'Road Reclaimers', 'Skid Steer Loaders', 'Telehandlers', 'Wheel Loaders', 'Wheel Tractor Scrapers', 'Articulated Trucks', 'Draglines', 'Drill Rigs', 'Electric Rope Shovels', 'Hydraulic Shovels', 'Material Handlers', 'Off-Highway Trucks', 'Track Loaders', 'Underground Hard Rock Loaders', 'Other',],
            // 'sub_categories' => ['Earthmoving and Grading', 'Excavation and Digging', 'Loading and Material Handling', 'Roadwork and Paving', 'Mining and Drilling', 'Specialty and Support Equipment', 'Yellow Metal Trucks', ],
        ],
        'Commercial Marine' => [
            'singular' => 'Commercial Marine',
            'long_name' => 'Commercial Marine Vehicles',
            'description' => 'Boats and ships for water travel.',
            'image_path' => 'https://images.unsplash.com/photo-1662657736642-83b53193d7f1?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'sub_categories' => ['Commercial Vessels', 'Specialized Vessels'],
        ],
        'Aviation' => [
            'singular' => 'Aviation',
            'long_name' => 'Aviation Vehicles',
            'description' => 'Aircraft and related vehicles for air travel.',
            'image_path' => 'https://images.unsplash.com/photo-1715063860227-2878cafb5917?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'sub_categories' => ['Ultralight Aircraft', 'Fixed Wing', 'Rotorcraft',],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Vehicle Sub-categories
    |--------------------------------------------------------------------------
    | A list of vehicle sub-categories
    | NO LONGER IN USE
    |
    */
    'sub_categories' => [
        'Motorcycles' => [
            'singular' => 'Motorcycle',
            'long_name' => 'Motorcycles & Powersports',
            'description' => 'Two-wheel freedom for speed, style, and adventure.',
            'image_path' => 'https://images.unsplash.com/photo-1609630875171-b1321377ee65?q=80&w=680&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'vehicle_types' => ['Standard', 'Cruiser', 'Touring', 'Sport', 'Off-road', 'Dual-purpose'],
            'fuel_types' => ['Gasoline', 'Electric', 'Hybrid', 'Diesel'],
        ],
        'ATVs' => [
            'singular' => 'ATV',
            'long_name' => 'All-Terrain Vehicles',
            'description' => 'Off-road vehicles for various terrains.',
            'image_path' => 'https://images.unsplash.com/photo-1609630875171-b1321377ee65?q=80&w=680&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'vehicle_types' => ['Standard', 'Sport', 'Utility', 'Youth', 'Electric'],
            'fuel_types' => ['Gasoline', 'Electric', 'Hybrid', 'Diesel'],
        ],
        'UTVs' => [
            'singular' => 'UTV',
            'long_name' => 'Utility Task Vehicles',
            'description' => 'Versatile vehicles for off-road and utility tasks.',
            'image_path' => 'https://images.unsplash.com/photo-1609630875171-b1321377ee65?q=80&w=680&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'vehicle_types' => ['Standard', 'Crew', 'Sport', 'Utility', 'Crossover', 'Electric'],
            'fuel_types' => ['Gasoline', 'Electric', 'Hybrid', 'Diesel'],
        ],
        'Snowmobiles' => [
            'singular' => 'Snowmobile',
            'long_name' => 'Snowmobiles',
            'description' => 'Vehicles for winter travel on snow and ice.',
            'image_path' => 'https://images.unsplash.com/photo-1609630875171-b1321377ee65?q=80&w=680&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'vehicle_types' => ['Standard', 'Sport', 'Utility', 'Youth', 'Electric'],
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
            'description' => 'Trailers for all your hauling needs.',
            'image_path' => 'https://images.unsplash.com/photo-1617085979589-a61ffc926e13?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'vehicle_types' => ['Single Axle', 'Double Axle', 'Gooseneck', 'Fifth Wheel', 'Toy Hauler', 'Pop-up'],
            'fuel_types' => [],
        ],
        'Caravans' => [
            'singular' => 'Caravan',
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
        'Personal Watercraft' => [
            'singular' => 'Personal Watercraft',
            'long_name' => 'Personal Watercraft & Jet Skis',
            'description' => 'Jet Ski, Sea-Doo, WaveRunner.',
            'image_path' => 'https://images.unsplash.com/photo-1622037764752-6cda75f99a9f?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'vehicle_types' => ['Fishing Boat', 'Sailboat', 'Yacht', 'Pontoon Boat', 'Jet Ski', 'Rubber Duck'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Motorboats' => [
            'singular' => 'Motorboat',
            'long_name' => 'Motorized Boats & Jetskis',
            'description' => 'Versatile vehicles for both work and play.',
            'image_path' => 'https://images.unsplash.com/photo-1622037764752-6cda75f99a9f?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'vehicle_types' => ['Fishing Boat', 'Sailboat', 'Yacht', 'Pontoon Boat', 'Rubber Duck'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Sailboats' => [
            'singular' => 'Sailboat',
            'long_name' => 'Sailboats & Non-Motorized Boats',
            'description' => 'Versatile vehicles for both work and play.',
            'image_path' => 'https://images.unsplash.com/photo-1622037764752-6cda75f99a9f?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'vehicle_types' => ['Fishing Boat', 'Sailboat', 'Yacht', 'Pontoon Boat', 'Jet Ski', 'Rubber Duck'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Commercial Vessels' => [
            'singular' => 'Commercial Vessel',
            'long_name' => 'Commercial Vessels & Work Boats',
            'description' => 'Versatile vehicles for both work and play.',
            'image_path' => 'https://images.unsplash.com/photo-1622037764752-6cda75f99a9f?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'vehicle_types' => ['Fishing Boat', 'Sailboat', 'Yacht', 'Pontoon Boat', 'Jet Ski', 'Rubber Duck'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Specialized Vessels' => [
            'singular' => 'Specialized Vessel',
            'long_name' => 'Specialized Vessels & Work Boats',
            'description' => 'Versatile vehicles for both work and play.',
            'image_path' => 'https://images.unsplash.com/photo-1622037764752-6cda75f99a9f?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'vehicle_types' => ['Heavy-Lift Ship', 'Icebreaker', 'Research Vessel', 'LNG/LPG Carrier', 'Tugboat', 'Pilot Boat', 'Dredger', 'Hovercraft', 'Submarine/Submersible', 'Military Vessel', 'Other'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Minibuses' => [
            'singular' => 'Minibus',
            'long_name' => 'Minibuses and Panel Vans',
            'description' => 'Spacious vehicles for group travel and transport.',
            'image_path' => 'https://c.anibis.ch/big/7596426995.jpg',
            'vehicle_types' => ['Standard', 'High Roof', 'Luxury', 'Cargo', 'Passenger', 'Panel Van'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        // 'Panel Vans' => [
        //     'singular' => 'Panel Van',
        //     'long_name' => 'Panel Vans',
        //     'description' => 'Practical vehicles for business and cargo needs.',
        //     'image_path' => 'https://images.pexels.com/photos/7763831/pexels-photo-7763831.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
        //     'vehicle_types' => ['Cargo Van', 'Passenger Van', 'Crew Van', 'Camper Van'],
        //     'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        // ],
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
        // 'Agri Plant Machinery' => [
        //     'singular' => 'Agri Machine',
        //     'long_name' => 'Agri Plant Machinery',
        //     'description' => 'Robust vehicles designed for farming and agricultural tasks.',
        //     'image_path' => 'https://images.pexels.com/photos/32958981/pexels-photo-32958981/free-photo-of-high-powered-ace-tractor-in-faridabad-india.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
        //     'vehicle_types' => ['Tractor', 'Combine Harvester', 'Plough', 'Seeder', 'Baler', 'Sprayer'],
        //     'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        // ],
        'Farm Tractors' => [
            'singular' => 'Farm Tractor',
            'long_name' => 'Farm Tractors',
            'description' => 'Farm tractors for agricultural tasks.',
            'image_path' => 'https://images.pexels.com/photos/32958981/pexels-photo-32958981/free-photo-of-high-powered-ace-tractor-in-faridabad-india.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Tractor', 'Combine Harvester', 'Plough', 'Seeder', 'Baler', 'Sprayer'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        'Industrial Tractors' => [
            'singular' => 'Industrial Tractor',
            'long_name' => 'Industrial Tractors',
            'description' => 'Heavy-duty tractors designed for industrial applications.',
            'image_path' => 'https://images.pexels.com/photos/32958981/pexels-photo-32958981/free-photo-of-high-powered-ace-tractor-in-faridabad-india.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Tractor', 'Combine Harvester', 'Plough', 'Seeder', 'Baler', 'Sprayer'],
            'fuel_types' => ['Gasoline', 'Diesel', 'Electric', 'Hybrid'],
        ],
        // 'Aircraft' => [
        //     'singular' => 'Aircraft',
        //     'long_name' => 'Planes and Aviation',
        //     'description' => 'Aircraft designed for various purposes.',
        //     'image_path' => 'https://images.pexels.com/photos/17485431/pexels-photo-17485431/free-photo-of-light-aircraft-on-field.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
        //     'vehicle_types' => ['Single Engine', 'Multi Engine', 'Helicopter', 'Glider', 'Seaplane', 'Jet'],
        //     'fuel_types' => ['AvGas', 'Jet Fuel', 'Electric', 'Hybrid'],
        // ],
        'Fixed Wings' => [
            'singular' => 'Fixed Wing Aircraft',
            'long_name' => 'Planes and Aviation',
            'description' => 'Aircraft designed for various purposes.',
            'image_path' => 'https://images.pexels.com/photos/17485431/pexels-photo-17485431/free-photo-of-light-aircraft-on-field.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Single Engine', 'Multi Engine', 'Helicopter', 'Glider', 'Seaplane', 'Jet', 'Amphibious/Seaplane', 'Military Aircraft', 'Other'],
            'fuel_types' => ['AvGas', 'Jet Fuel', 'Electric', 'Hybrid'],
        ],
        'Rotorcraft' => [
            'singular' => 'Rotorcraft',
            'long_name' => 'Rotorcraft and Helicopters',
            'description' => 'Aircraft that use rotor systems for lift.',
            'image_path' => 'https://images.pexels.com/photos/17485431/pexels-photo-17485431/free-photo-of-light-aircraft-on-field.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Helicopter', 'Gyrocopter', 'Tiltrotor', 'Autogyro'],
            'fuel_types' => ['AvGas', 'Jet Fuel', 'Electric', 'Hybrid'],
        ],
        'Ultralight Aircraft' => [
            'singular' => 'Ultralight Aircraft',
            'long_name' => 'Ultralight Aircraft, Gliders, and Homebuilts',
            'description' => 'Lightweight aircraft designed for easy handling and minimal regulations.',
            'image_path' => 'https://images.pexels.com/photos/17485431/pexels-photo-17485431/free-photo-of-light-aircraft-on-field.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'vehicle_types' => ['Glider/Sailplane', 'Ultralight', 'Experimental/Homebuilt', ],
            'fuel_types' => ['AvGas', 'Jet Fuel', 'Electric', 'Hybrid'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Fuel Types
    |--------------------------------------------------------------------------
    | A static list of fuel types used across the application.
    |
    */
    'fuel_types' => [
        'Powersport' => [
            'Petrol',
            'Battery Electric',
            'Diesel',
            'Flex-Fuel(FFV)',
        ],
        'Internal Combustion' => [
            'Petrol',
            'Diesel',
            'Dual Fuel',
            'Flex-Fuel(FFV)',
        ],
        'Electric' => [
            'Battery Electric',
            'Hybrid Electric(HEV)',
            'Hydrogen Fuel Cell Electric(FCEV)',
            'Plug-in Hybrid Electric(PHEV)',
        ],
        'High Pressure Gas' => [
            'Compressed Natural Gas(CNG)',
            'Liquid Petrolium Gas(LPG)',
        ],
        'None' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Transmissions
    |--------------------------------------------------------------------------
    */
    'transmissions' => [
        'Manual' => [
            '3-Speed Manual',
            '4-Speed Manual',
            '5-Speed Manual',
            '6-Speed Manual',
        ],
        'Automatic' => [
            '3-Speed Automatic',
            '4-Speed Automatic',
            '5-Speed Automatic',
            '6-Speed Automatic',
            '8-Speed Automatic',
            '10-Speed Automatic',
        ],
        'Advanced' => [
            'Continuously Variable Transmission (CVT)',
            'Dual-Clutch Transmission (DCT)',
            'Automated Manual Transmission (AMT)',
            'Direct-Drive'
        ],
        'Truck Manual' => [
            '5-Speed Manual',
            '6-Speed Manual',
            '10-Speed Manual',
            '13-Speed Manual',
            '18-Speed Manual',
        ],
        'None' => [
            'None / Not Specified', // <-- UPDATED
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Drive Train
    |--------------------------------------------------------------------------
    */
    'drive_train' => [
        'Standard' => [
            'Front-Wheel Drive (FWD)',
            'Rear-Wheel Drive (RWD)',
        ],
        'Advanced' => [
            'All-Wheel Drive (AWD)',
            'Four-Wheel Drive (4WD)',
            'Part-Time 4WD',
            'Full-Time 4WD',
        ],
        'Special' => [
            'Six-Wheel Drive (6x6)',
            'Tracked Drive',
        ],
        'None' => [
            'None / Not Specified', // <-- UPDATED
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    */
    'features' => [
        'Comfort and Convenience' => [
            'Leather Seats',
            'Heated Seats',
            'Power Windows',
            'Bluetooth'
        ],
        'Safety Features' => [
            'Airbags',
            'ABS',
            'Backup Camera',
            'Lane Departure Warning',
        ],
        'Technology' => [
            'GPS Navigation',
            'Premium Sound System',
            'Apple CarPlay/Android Auto',
        ],
        'Modifications (Trucks)' => [
            'Lift Kits',
            'Custom Rims',
            'Performance Upgrades',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Exterior Colours
    |--------------------------------------------------------------------------
    | A static list of exterior vehicle colours
    |
    */
    'colors' => [
        'Standard' => [
            'White',
            'Black',
            'Silver',
            'Grey',
            'Blue',
            'Red',
        ],
        'Metallic' => [
            'Metallic Silver',
            'Metallic Grey',
            'Metallic Blue',
            'Metallic Black',
            'Metallic Red',
            'Gunmetal',
        ],
        'Matte' => [
            'Matte Black',
            'Matte Grey',
            'Matte Blue',
            'Matte White',
            'Matte Green',
        ],
        'Special' => [
            'Pearl White',
            'Champagne',
            'Bronze',
            'Burgundy',
            'Forest Green',
            'Navy Blue',
            'Yellow',
            'Orange',
        ],
        'Other' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Interior
    |--------------------------------------------------------------------------
    | A static list of interior vehicle options
    |
    */
    'interior' => [
        'Cloth' => [
            'Colours' => [
                'Black',
                'Grey',
                'Beige',
                'Blue',
                'Red',
            ],
        ],
        'Premium Cloth' => [
            'Colours' => [
                'Black',
                'Grey',
                'Tan',
                'Brown',
            ],
        ],
        'Leather' => [
            'Colours' => [
                'Black',
                'Beige',
                'Brown',
                'Tan',
                'Grey',
                'White',
            ],
        ],
        'Synthetic Leather (Leatherette)' => [
            'Colours' => [
                'Black',
                'Dark Grey',
                'Beige',
                'Brown',
            ],
        ],
        'Nappa Leather' => [
            'Colours' => [
                'Black',
                'Cognac',
                'Ivory',
                'Deep Red',
            ],
        ],
        'Suede Leather' => [
            'Colours' => [
                'Black',
                'Grey',
                'Brown',
                'Navy Blue',
            ],
        ],
        'Alcantara' => [
            'Colours' => [
                'Black',
                'Dark Grey',
                'Light Grey',
                'Burgundy',
            ],
        ],
        'Vinyl' => [
            'Colours' => [
                'Black',
                'Grey',
                'Tan',
            ],
        ],
        'None' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Accident History
    |--------------------------------------------------------------------------
    | A static list of history and documentation
    |
    */
    'accident_history' => [
        'None' => ['No Accidents Reported'],
        'Minor' => [
            'Scratch / Paint Damage',
            'Small Dent',
            'Windshield Crack',
        ],
        'Moderate' => [
            'Front Bumper Repair',
            'Rear Bumper Repair',
            'Panel Replacement',
            'Suspension Damage',
        ],
        'Severe' => [
            'Structural Damage',
            'Airbag Deployment',
            'Chassis Repair',
            'Stolen and Recovered',
            'Flood or Fire Damage',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Exterior Condition
    |--------------------------------------------------------------------------
    | A static list of the exterior condition
    |
    */
    'exterior_condition' =>
        [
            'New,
            Like New,
            Excellent,
            Good,
            Fair,
            Needs Work'
        ],

    /*
    |--------------------------------------------------------------------------
    | Interior Condition
    |--------------------------------------------------------------------------
    | A static list of the interior condition
    |
    */
    'interior_condition' =>
        [
            'New,
            Like New,
            Excellent,
            Good,
            Fair,
            Needs Work'
        ],

    /*
    |--------------------------------------------------------------------------
    | Mechanical Condition
    |--------------------------------------------------------------------------
    | A static list of the mechanical condition
    |
    */
    'Mechanical Condition' =>
        [
            'New',
            'Like New',
            'Excellent',
            'Good',
            'Fair',
            'Needs Work'
        ],



    /*
    |--------------------------------------------------------------------------
    | Service History
    |--------------------------------------------------------------------------
    | A static list of history and documentation
    |
    */
    'Service History' =>[
        'Full History',
        'Partial History',
        'No History'
    ],
];
