<?php

namespace Wing5wong\KamarDirectoryService\Responses\Standard;

use Wing5wong\KamarDirectoryService\Responses\AbstractResponse;

class FailedAuthentication extends AbstractResponse
{
    protected int $error = 403;
    protected string $result = "Forbidden";
}
