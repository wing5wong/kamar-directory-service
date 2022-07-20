<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function test_unauthenticated_request_returns_403()
    {
        $response = $this->postJson('/kamar');

        $response->assertJson(['SMSDirectoryData' => ['error' => 403]]);
        $response->assertJson(['SMSDirectoryData' => ['result' => 'Authentication Failed']]);
        $response->assertJsonMissing(['SMSDirectoryData' => ['service' => config('kamar.serviceName')]]);
        $response->assertJsonMissing(['SMSDirectoryData' => ['version' => config('kamar.serviceVersion')]]);
    }

    public function test_unauthenticated_check_request_returns_403_with_service_details()
    {
        $response = $this->postJson('/kamar', ['SMSDirectoryData'=>['sync'=>'check']]);

        $response->assertJson(['SMSDirectoryData' => ['error' => 403]]);
        $response->assertJson(['SMSDirectoryData' => ['result' => 'Authentication Failed']]);
        $response->assertJson(['SMSDirectoryData' => ['service' => config('kamar.serviceName')]]);
        $response->assertJson(['SMSDirectoryData' => ['version' => config('kamar.serviceVersion')]]);
    }

    public function test_invalid_credentials_returns_403()
    {
        $response = $this->withHeaders([
            'HTTP_AUTHORIZATION' => "Basic " . base64_encode('Username' . ':' . 'Password'),
        ])->postJson('/kamar');

        $response->assertJson(['SMSDirectoryData' => ['error' => 403]]);
    }

    public function test_authenticated_request_with_missing_data_returns_401()
    {
        $response = $this->withHeaders([
            'HTTP_AUTHORIZATION' => "Basic " . base64_encode(config('kamar.username') . ':' . config('kamar.password')),
        ])->postJson('/kamar');

        $response->assertJson(['SMSDirectoryData' => ['error' => 401]]);
        $response->assertJson(['SMSDirectoryData' => ['result' => 'Missing Data']]);
        $response->assertJsonMissing(['SMSDirectoryData' => ['service' => config('kamar.serviceName')]]);
        $response->assertJsonMissing(['SMSDirectoryData' => ['version' => config('kamar.serviceVersion')]]);
    }

    public function test_authenticated_sync_check_request_with_data_returns_0_and_service()
    {
        $response = $this->withHeaders([
            'HTTP_AUTHORIZATION' => "Basic " . base64_encode(config('kamar.username') . ':' . config('kamar.password')),
        ])->postJson('/kamar', ['SMSDirectoryData'=>['sync'=>'check']]);

        $response->assertJson(['SMSDirectoryData' => ['error' => 0]]);
        $response->assertJson(['SMSDirectoryData' => ['result' => 'OK']]);
        $response->assertJson(['SMSDirectoryData' => ['service' => config('kamar.serviceName')]]);
        $response->assertJson(['SMSDirectoryData' => ['version' => config('kamar.serviceVersion')]]);
    }

    public function test_authenticated_request_with_data_returns_0_and_no_service_details()
    {
        $response = $this->withHeaders([
            'HTTP_AUTHORIZATION' => "Basic " . base64_encode(config('kamar.username') . ':' . config('kamar.password')),
        ])->postJson('/kamar', ['SMSDirectoryData'=>['sync'=>'part']]);

        $response->assertJson(['SMSDirectoryData' => ['error' => 0]]);
        $response->assertJson(['SMSDirectoryData' => ['result' => 'OK']]);
        $response->assertJsonMissing(['SMSDirectoryData' => ['service' => config('kamar.serviceName')]]);
        $response->assertJsonMissing(['SMSDirectoryData' => ['version' => config('kamar.serviceVersion')]]);
    }

}
