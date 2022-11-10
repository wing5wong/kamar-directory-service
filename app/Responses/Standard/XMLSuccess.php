<?php

namespace App\Responses\Standard;

use App\Responses\AbstractXMLResponse;

class XMLSuccess extends AbstractXMLResponse

{
    protected int $error = 0;
    protected string $result = "OK";
}
