<?php

namespace App\Responses\Check;

class MissingData extends AbstractCheckResponse
{
    protected int $error = 401;
    protected string $result = "Missing Data";
}
