<?php

namespace App\Models\Responses;

use Illuminate\Contracts\Support\Arrayable;

class SyncCheck implements Arrayable
{
    private string $service;
    private float $version;
    public string$infoUrl;
    public string $privacyStatement;
    public array $options;

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
                'error' => 0,
                'result' => 'OK',
                "status" => "Ready",
                "service" => $this->service,
                "version" =>  $this->version,
                "infourl" => $this->infoUrl,
                "privacystatement" => $this->privacyStatement,
                "options" => $this->options,
            ]
        ];
    }
}
