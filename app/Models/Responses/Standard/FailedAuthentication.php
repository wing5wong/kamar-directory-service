<?php

namespace App\Models\Responses\Standard;

use App\Models\Responses\AbstractResponse;

class FailedAuthentication extends AbstractResponse
{
    protected int $error = 403;
    protected string $result = "Authentication Failed";
}
