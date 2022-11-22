<?php

namespace Tests\Unit;

use App\Auth\AuthenticationCheck;
use Tests\TestCase;
use Illuminate\Http\Request;

class AuthenticationCheckTest extends TestCase
{

    public function test_invalid_credentials()
    {
        $request = new Request();
        $request->server->replace(['HTTP_AUTHORIZATION'=>"Basic " . base64_encode('username' . ':' . 'password')]);
        app()->instance('request', $request);

        $this->assertTrue((new AuthenticationCheck())->fails());
    }
    
    public function test_valid_credentials()
    {
        $request = new Request();
        $request->server->replace(['HTTP_AUTHORIZATION'=>"Basic " . base64_encode(config('kamar.username') . ':' . config('kamar.password'))]);
        app()->instance('request', $request);

        $this->assertFalse((new AuthenticationCheck())->fails());
    }
}
