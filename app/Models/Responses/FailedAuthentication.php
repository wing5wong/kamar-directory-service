<?php

namespace App\Models\Responses;

class FailedAuthentication extends AbstractFailedResponse
{
    protected int $error = 403;
    protected string $result = "Authentication Failed";
}
