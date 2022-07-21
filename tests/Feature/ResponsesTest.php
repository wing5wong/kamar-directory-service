<?php

namespace Tests\Feature;

use Tests\TestCase;

class ResponsesTest extends TestCase
{
    public function test_unauthenticated_standard_requests_return_403()
    {
        $response = $this->postJson('/kamar');

        $response->assertJson([
            'SMSDirectoryData' => [
                'error' => 403,
                'result' => 'Authentication Failed',
            ]
        ]);

        $response->assertJsonMissing([
            'SMSDirectoryData' => [
                'service' => config('kamar.serviceName'),
                'version' => config('kamar.serviceVersion')
            ]
        ]);
    }

    public function test_unauthenticated_check_requests_return_403_with_service_details()
    {
        $response = $this->postJson('/kamar', [
            'SMSDirectoryData' => [
                'sync' => 'check'
            ]
        ]);

        $response->assertJson([
            'SMSDirectoryData' => [
                'error' => 403,
                'result' => 'Authentication Failed',
                'service' => config('kamar.serviceName'),
                'version' => config('kamar.serviceVersion'),
            ]
        ]);
    }

    public function test_standard_requests_with_invalid_credentials_return_403()
    {
        $response = $this->withHeaders([
            'HTTP_AUTHORIZATION' => $this->invalidCredentials(),
        ])->postJson('/kamar');

        $response->assertJson([
            'SMSDirectoryData' => [
                'error' => 403,
                'result' => 'Authentication Failed',
            ]
        ]);

        $response->assertJsonMissing([
            'SMSDirectoryData' => [
                'service' => config('kamar.serviceName'),
                'version' => config('kamar.serviceVersion')
            ]
        ]);
    }

    public function test_check_requests_with_invalid_credentials_return_403_with_service_details()
    {
        $response = $this->withHeaders([
            'HTTP_AUTHORIZATION' => $this->invalidCredentials(),
        ])->postJson('/kamar', [
            'SMSDirectoryData' => [
                'sync' => 'check'
            ]
        ]);

        $response->assertJson([
            'SMSDirectoryData' => [
                'error' => 403,
                'result' => 'Authentication Failed',
                'service' => config('kamar.serviceName'),
                'version' => config('kamar.serviceVersion'),
            ]
        ]);
    }

    public function test_authenticated_standard_requests_with_blank_data_return_401()
    {
        $response = $this->withHeaders([
            'HTTP_AUTHORIZATION' => $this->validCredentials(),
        ])->postJson('/kamar');

        $response->assertJson([
            'SMSDirectoryData' => [
                'error' => 401,
                'result' => 'Missing Data',
            ]
        ]);

        $response->assertJsonMissing([
            'SMSDirectoryData' => [
                'service' => config('kamar.serviceName'),
                'version' => config('kamar.serviceVersion')
            ]
        ]);
    }

    public function test_authenticated_standard_requests_with_empty_data_return_401()
    {
        $response = $this->withHeaders([
            'HTTP_AUTHORIZATION' => $this->validCredentials(),
        ])->postJson('/kamar', []);

        $response->assertJson([
            'SMSDirectoryData' => [
                'error' => 401,
                'result' => 'Missing Data',
            ]
        ]);

        $response->assertJsonMissing([
            'SMSDirectoryData' => [
                'service' => config('kamar.serviceName'),
                'version' => config('kamar.serviceVersion')
            ]
        ]);
    }

    public function test_authenticated_check_requests_return_0_and_service_details()
    {
        $response = $this->withHeaders([
            'HTTP_AUTHORIZATION' => $this->validCredentials(),
        ])->postJson('/kamar', [
            'SMSDirectoryData' => [
                'sync' => 'check'
            ]
        ]);

        $response->assertJson([
            'SMSDirectoryData' => [
                'error' => 0,
                'result' => 'OK',
                'service' => config('kamar.serviceName'),
                'version' => config('kamar.serviceVersion'),
            ]
        ]);
    }

    public function test_authenticated_standard_requests_return_0()
    {
        $response = $this->withHeaders([
            'HTTP_AUTHORIZATION' => $this->validCredentials(),
        ])->postJson('/kamar', [
            'SMSDirectoryData' => [
                'sync' => 'part'
            ]
        ]);

        $response->assertJson([
            'SMSDirectoryData' => [
                'error' => 0,
                'result' => 'OK',
            ]
        ]);

        $response->assertJsonMissing([
            'SMSDirectoryData' => [
                'service' => config('kamar.serviceName'),
                'version' => config('kamar.serviceVersion')
            ]
        ]);
    }

    private function validCredentials()
    {
        return "Basic " . base64_encode(config('kamar.username') . ':' . config('kamar.password'));
    }

    private function invalidCredentials()
    {
        return "Basic " . base64_encode('username' . ':' . 'password');
    }
}
