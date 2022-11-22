<?php

namespace Wing5wong\KamarDirectoryService\Responses\Standard;

use Wing5wong\KamarDirectoryService\Responses\AbstractXMLResponse;

class XMLFailedAuthentication extends AbstractXMLResponse
{
    protected int $error = 403;
    protected string $result = "Forbidden";
}
