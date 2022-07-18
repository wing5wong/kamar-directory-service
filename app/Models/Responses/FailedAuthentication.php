<?php

namespace App\Models\Responses;

use Illuminate\Contracts\Support\Arrayable;

class FailedAuthentication implements Arrayable
{   
    private string $service;
    private float $version;

    public function __construct() {
        $this->service = config('kamar.serviceName');
        $this->version = config('kamar.serviceVersion');
    }

    public function toArray()
    {
        return [
            'SMSDirectoryData' => [
                'error' => 403,
                'result' => 'Authentication Failed',
                "service" => $this->service,
                "version" =>  $this->version,
            ]
        ];
    }
}
