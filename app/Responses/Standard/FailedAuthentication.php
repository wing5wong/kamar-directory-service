<?php

namespace App\Responses\Standard;

use App\Responses\AbstractResponse;

class FailedAuthentication extends AbstractResponse
{
    protected int $error = 403;
    protected string $result = "Forbidden";
}
