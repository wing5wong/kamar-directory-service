<?php

namespace App\Models\Responses\Standard;

use App\Models\Responses\AbstractResponse;

class MissingData extends AbstractResponse
{
    protected int $error = 401;
    protected string $result = "Missing Data";
}
