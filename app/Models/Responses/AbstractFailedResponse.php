<?php

namespace App\Models\Responses;

abstract class AbstractFailedResponse extends AbstractResponse
{
    protected int $error;
    protected string $result;
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
