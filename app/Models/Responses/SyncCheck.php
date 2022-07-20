<?php

namespace App\Models\Responses;

class SyncCheck extends AbstractResponse
{
    protected int $error = 0;
    protected string $result = "OK";
    protected string $status = "Ready";

    protected string $service;
    protected float $version;
    protected string $infoUrl;
    protected string $privacyStatement;
    protected array $options;

    public function __construct()
    {
        $this->service = config('kamar.serviceName');
        $this->version = config('kamar.serviceVersion');
        $this->infoUrl = config('kamar.infoUrl');
        $this->privacyStatement = config('kamar.privacyStatement');
        $this->options = config('kamar.options');
    }

    public function toArray()
    {
        return [
            'SMSDirectoryData' => [
                'error' => $this->error,
                'result' => $this->result,
                "status" => $this->status,
                "service" => $this->service,
                "version" =>  $this->version,
                "infourl" => $this->infoUrl,
                "privacystatement" => $this->privacyStatement,
                "options" => $this->options,
            ]
        ];
    }
}
