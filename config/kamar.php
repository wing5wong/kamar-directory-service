<?php

return [

    'username' =>  env('KAMAR_DS_USERNAME'),
    'password' => env('KAMAR_DS_PASSWORD'),

    'serviceName' => 'Kamar Directory Service',
    'serviceVersion' => 1.0,

    'infoUrl' => 'https://www.myschool.co.nz/more-info',
    'privacyStatement' => 'Change this to a valid privacy statement | All your data belongs to us and will be kept in a top secret vault.',

    'options' => [

        "ics" => false,

        "students" => [
            "details" => true,
            "passwords" => false,
            "photos" => false,
            "groups" => false,
            "awards" => false,
            "timetables" => false,
            "attendance" => false,
            "assessments" => false,
            "pastoral" => true,
            "learningsupport" => false,
            "fields" => [
                "required" =>  "uniqueid;schoolindex;nsn;firstname;lastname",
                "optional" => "username;password"
            ]
        ],

        "staff" => [
            "details" => false,
            "passwords" => false,
            "photos" => false,
            "timetables" => false,
            "fields" => [
                "required" =>  "uniqueid;schoolindex;firstname;lastname",
                "optional" => "title;classification"
            ]
        ],

        "common" => [
            "subjects" => false,
            "notices" => false,
            "calendar" => false,
            "bookings" => false
        ]
    ],

];
