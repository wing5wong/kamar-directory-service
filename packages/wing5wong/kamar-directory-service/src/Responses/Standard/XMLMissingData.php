<?php

namespace Wing5wong\KamarDirectoryService\Responses\Standard;

use Wing5wong\KamarDirectoryService\Responses\AbstractXMLResponse;

class XMLMissingData extends AbstractXMLResponse
{
    protected int $error = 400;
    protected string $result = "Bad Request";
}
