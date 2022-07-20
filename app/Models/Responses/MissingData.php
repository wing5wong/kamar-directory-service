<?php

namespace App\Models\Responses;

class MissingData extends AbstractFailedResponse
{
    protected int $error = 401;
    protected string $result = "Missing Data";
}
