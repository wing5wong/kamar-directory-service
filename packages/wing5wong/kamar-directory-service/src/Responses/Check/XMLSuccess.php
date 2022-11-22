<?php

namespace Wing5wong\KamarDirectoryService\Responses\Check;

use Wing5wong\KamarDirectoryService\Responses\AbstractXMLResponse;

class XMLSuccess extends AbstractXMLResponse
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
        
        $this->additionalFields = [
            'status' => $this->status,
            'infourl' => $this->infoUrl,
            'privacystatement' => $this->privacyStatement,
            'options' => $this->options,
        ];
    }
}
