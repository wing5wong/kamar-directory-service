<?php

namespace App\Models\Responses;

class FailedAuthentication extends AbstractResponse
{
    protected int $error = 403;
    protected string $result = "Authentication Failed";
}
