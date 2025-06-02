<?php

return [

    'username' =>  env('KAMAR_DS_USERNAME', 'My Kamar directory service username'),
    'password' => env('KAMAR_DS_PASSWORD', 'My Kamar directory service password'),
    'encryptionKey' => env('KAMAR_ENCRYPTION_KEY', 'My Kamar directory service encryption key, if applicable'),
    'encryptionAlgorithm' => env('KAMAR_ENCRYPTION_ALGORITHM', 'aes-128-ecb'),

    'storageDisk' => env('KAMAR_DS_STORAGE_DISK', 'local'),
    'storageFolder' => env('KAMAR_DS_STORAGE_FOLDER', 'data'),

    'format' => env('KAMAR_DS_FORMAT'),

    'serviceName' => 'Kamar Directory Service',
    'serviceVersion' => 1.0,
    'countryDataStored' => 'New Zealand',

    'authSuffix' => env('KAMAR_DS_AUTH_SUFFIX'),

    'infoUrl' => 'https://www.whanganuihigh.school.nz',
    'privacyStatement' => 'Data isused for managing user accounts within Whanganui High School only.',

    'options' => [

        "ics" => false,

        "students" => [
            "details" => true,
            "passwords" => true,
            "photos" => false,
            "groups" => true,
            "awards" => false,
            "timetables" => false,
            "attendance" => true,
            "assessments" => false,
            "pastoral" => true,
            "recognitions" => true,
            "learningsupport" => false,
            "fields" => [
                "required" =>  "uniqueid;firstname;lastname;username",
                "optional" => "schoolindex;nsn;yearlevel;leavingdate;tutor;house;ethnicityL1;ethnicityL2;ethnicity,gender"
            ]
        ],

        "staff" => [
            "details" => true,
            "passwords" => false,
            "photos" => false,
            "timetables" => false,
            "fields" => [
                "required" =>  "uniqueid;firstname;lastname;username",
                "optional" => "schoolindex;title;position;classification;tutor;house;leavingdate;groups.departments;photocopierid;extension"
            ]
        ],

        "common" => [
            "subjects" => false,
            "notices" => true,
            "calendar" => false,
            "bookings" => false
        ]
    ],

    'vivi' => [
        'apiKey' => env('VIVI_API_KEY'),
        'emergencyTypeId' => env('VIVI_EMERGENCY_TYPE_ID'),
    ]
];
