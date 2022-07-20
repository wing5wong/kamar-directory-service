<?php

namespace App\Responses\Standard;

use App\Responses\AbstractResponse;

class MissingData extends AbstractResponse
{
    protected int $error = 401;
    protected string $result = "Missing Data";
}
