<?php
// config/locations.php

return [
    /*
    |--------------------------------------------------------------------------
    | Geographic Data
    |--------------------------------------------------------------------------
    | A list of South African provinces with their corresponding cities and
    | approximate geographic coordinates. This data is used for testing and
    | development purposes.
    |
    */

    'provinces' => [
        'Western Cape' => [
            'coordinates' => ['lat' => -33.0, 'lng' => 19.5],
            'cities' => [
                'Cape Town',
                'Stellenbosch',
                'George',
                'Paarl',
                'Worcester',
                'Hermanus',
                'Mossel Bay',
                'Oudtshoorn',
            ],
        ],
        'Gauteng' => [
            'coordinates' => ['lat' => -26.0, 'lng' => 28.0],
            'cities' => [
                'Johannesburg',
                'Pretoria',
                'Sandton',
                'Soweto',
                'Centurion',
                'Midrand',
                'Roodepoort',
                'Benoni',
            ],
        ],
        'KwaZulu-Natal' => [
            'coordinates' => ['lat' => -28.8, 'lng' => 30.5],
            'cities' => [
                'Durban',
                'Pietermaritzburg',
                'Umhlanga',
                'Ballito',
                'Richards Bay',
                'Newcastle',
                'Ladysmith',
                'Empangeni',
            ],
        ],
        'Eastern Cape' => [
            'coordinates' => ['lat' => -32.3, 'lng' => 26.5],
            'cities' => [
                'Port Elizabeth',
                'East London',
                'Makhanda',
                'Uitenhage',
                'Queenstown',
                'King Williams Town',
                'Graaff-Reinet',
            ],
        ],
        'Limpopo' => [
            'coordinates' => ['lat' => -24.0, 'lng' => 29.5],
            'cities' => [
                'Polokwane',
                'Mokopane',
                'Tzaneen',
                'Musina',
                'Thohoyandou',
                'Phalaborwa',
                'Louis Trichardt',
            ],
        ],
        'Mpumalanga' => [
            'coordinates' => ['lat' => -25.5, 'lng' => 30.5],
            'cities' => [
                'Mbombela',
                'Witbank',
                'Middelburg',
                'Secunda',
                'Ermelo',
                'Standerton',
                'Barberton',
            ],
        ],
        'North West' => [
            'coordinates' => ['lat' => -26.5, 'lng' => 25.5],
            'cities' => [
                'Mahikeng',
                'Rustenburg',
                'Potchefstroom',
                'Klerksdorp',
                'Brits',
                'Vryburg',
                'Lichtenburg',
            ],
        ],
        'Northern Cape' => [
            'coordinates' => ['lat' => -29.0, 'lng' => 22.0],
            'cities' => [
                'Kimberley',
                'Upington',
                'Springbok',
                'Kuruman',
                'De Aar',
                'Postmasburg',
                'Prieska',
            ],
        ],
        'Free State' => [
            'coordinates' => ['lat' => -28.5, 'lng' => 26.5],
            'cities' => [
                'Bloemfontein',
                'Welkom',
                'Bethlehem',
                'Kroonstad',
                'Sasolburg',
                'Phuthaditjhaba',
                'Virginia',
            ],
        ],
    ],
];
