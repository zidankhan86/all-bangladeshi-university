<?php

return [
    'tables' => [
        'universities' => env('BD_UNIVERSITIES_TABLE', 'bd_universities'),
        'campuses' => env('BD_UNIVERSITY_CAMPUSES_TABLE', 'bd_university_campuses'),
        'locations' => env('BD_UNIVERSITY_LOCATIONS_TABLE', 'bd_university_locations'),
    ],

    'dataset_path' => env('BD_UNIVERSITIES_DATASET_PATH'),

    'default_language' => 'en',

    'managed_by_package' => true,
];
