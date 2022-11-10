<?php

namespace App\Responses\Standard;

use App\Responses\AbstractXMLResponse;

class XMLMissingData extends AbstractXMLResponse
{
    protected int $error = 400;
    protected string $result = "Bad Request";
}
