<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PartUpdatesTest extends TestCase
{

    use RefreshDatabase;


    public function test_post_to_kamar_is_successfull()
    {
        $response = $this->postJson('/kamar', ['SMSDirectoryData' => []]);

        $response->assertStatus(200);
    }

    public function test_part_update_creates_student()
    {
        $response = $this->postJson(
            '/kamar',
            json_decode(
                '{
    "SMSDirectoryData": {
        "datetime": 20221122123112,
        "sync": "part",
        "sms": "KAMAR",
        "version": 2198,
        "schools": [
            {
                "index": 1,
                "schoolindex": 1,
                "moeCode": "0189",
                "name": "WHANGANUI HIGH SCHOOL",
                "type": 40,
                "authoritative": true
            }
        ],
        "students": {
            "count": 1,
            "data": [
                {
                    "id": 12345,
                    "created": 20221122123112,
                    "role": "Student",
                    "uniqueid": 1234567890,
                    "schoolindex": 1,
                    "username": "student.t.12345",
                    "password": "aDlsaW0hdU0=",
                    "firstname": "Test",
                    "lastname": "Student",
                    "yearlevel": 9
                }
            ]
        }
    }
}',
                true
            )
        );

        $this->assertDatabaseHas('students', [
            "uniqueid" => 1234567890,
            "studentid" => 12345
        ]);
    }
}
