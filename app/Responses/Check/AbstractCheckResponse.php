<?php

namespace App\Responses\Check;

use App\Responses\AbstractResponse;

abstract class AbstractCheckResponse extends AbstractResponse
{
    protected string $service;
    protected float $version;

    public function __construct()
    {
        $this->service = config('kamar.serviceName');
        $this->version = config('kamar.serviceVersion');
    }

    public function toArray()
    {
        return [
            'SMSDirectoryData' => [
                'error' => $this->error,
                'result' => $this->result,
                'service' => $this->service,
                'version' => $this->version,
            ]
        ];
    }
}
