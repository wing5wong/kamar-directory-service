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
            "passwords" => true,
            "photos" => false,
            "groups" => false,
            "awards" => false,
            "timetables" => true,
            "attendance" => false,
            "assessments" => false,
            "pastoral" => true,
            "learningsupport" => false,
            "fields" => [
                "required" =>  "uniqueid;schoolindex;nsn;firstname;lastname;email;",
                "optional" => "username;password;yearlevel;leavingdate;tutor"
            ]
        ],

        "staff" => [
            "details" => true,
            "passwords" => false,
            "photos" => false,
            "timetables" => true,
            "fields" => [
                "required" =>  "uniqueid;schoolindex;firstname;lastname;email",
                "optional" => "title;classification;tutor;leavingdate"
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
