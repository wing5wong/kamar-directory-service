<?php

namespace Wing5wong\KamarDirectoryService\Responses\Standard;

use Wing5wong\KamarDirectoryService\Responses\AbstractXMLResponse;

class XMLSuccess extends AbstractXMLResponse

{
    protected int $error = 0;
    protected string $result = "OK";
}
