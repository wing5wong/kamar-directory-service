<?php

namespace App\Responses\Check;

class FailedAuthentication extends AbstractCheckResponse
{
    protected int $error = 403;
    protected string $result = "Authentication Failed";
}
