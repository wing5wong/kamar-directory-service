<?php

namespace App\Responses\Check;

class Success extends AbstractCheckResponse
{
    protected int $error = 0;
    protected string $result = "OK";
    protected string $status = "Ready";
    protected string $infoUrl;
    protected string $privacyStatement;
    protected array $options;

    public function __construct()
    {
        parent::__construct();
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
