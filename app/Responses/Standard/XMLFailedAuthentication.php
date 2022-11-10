<?php

namespace App\Responses\Standard;

use App\Responses\AbstractXMLResponse;

class XMLFailedAuthentication extends AbstractXMLResponse
{
    protected int $error = 403;
    protected string $result = "Forbidden";
}
