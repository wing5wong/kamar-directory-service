<?php

namespace Wing5wong\KamarDirectoryService\Responses\Standard;

use Wing5wong\KamarDirectoryService\Responses\AbstractResponse;

class Success extends AbstractResponse
{
    protected int $error = 0;
    protected string $result = "OK";
}
