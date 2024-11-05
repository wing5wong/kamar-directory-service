<?php

return [

    'username' =>  env('KAMAR_DS_USERNAME', 'My Kamar directory service username'),
    'password' => env('KAMAR_DS_PASSWORD', 'My Kamar directory service password'),

    'encryptionKey' => env('KAMAR_ENCRYPTION_KEY', 'My Kamar directory service encryption key, if applicable'),

    'format' => env('KAMAR_DS_FORMAT'),

    'serviceName' => 'Kamar Directory Service',
    'serviceVersion' => 1.0,

    'authSuffix' => env('KAMAR_DS_AUTH_SUFFIX'),

    'infoUrl' => 'https://www.myschool.co.nz/more-info',
    'privacyStatement' => 'Change this to a valid privacy statement | All your data belongs to us and will be kept in a top secret vault.',

    'options' => [

        "ics" => false,

        "students" => [
            "details" => true,
            "passwords" => true,
            "photos" => false,
            "groups" => true,
            "awards" => false,
            "timetables" => false,
            "attendance" => false,
            "assessments" => false,
            "pastoral" => false,
            "learningsupport" => false,
            "fields" => [
                "required" =>  "uniqueid;firstname;lastname;username",
                "optional" => "schoolindex;nsn;yearlevel;leavingdate;tutor;house;ethnicityL1;ethnicityL2;ethnicity"
            ]
        ],

        "staff" => [
            "details" => false,
            "passwords" => false,
            "photos" => false,
            "timetables" => false,
            "fields" => [
                "required" =>  "uniqueid;firstname;lastname;username",
                "optional" => "schoolindex;title;position;classification;tutor;house;leavingdate;groups.departments"
            ]
        ],

        "common" => [
            "subjects" => false,
            "notices" => false,
            "calendar" => false,
            "bookings" => false
        ]
    ],

    'vivi' => [
        'apiKey' => env('VIVI_API_KEY'),
        'emergencyTypeId' => env('VIVI_EMERGENCY_TYPE_ID'),
    ]
];
