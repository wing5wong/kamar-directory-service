<?php

namespace Wing5wong\KamarDirectoryService\Responses\Standard;

use Wing5wong\KamarDirectoryService\Responses\AbstractResponse;

class MissingData extends AbstractResponse
{
    protected int $error = 400;
    protected string $result = "Bad Request";
}
