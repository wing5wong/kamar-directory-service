<?php

namespace App\Models\Responses\Standard;

use App\Models\Responses\AbstractResponse;

class Success extends AbstractResponse
{
    protected int $error = 0;
    protected string $result = "OK";
}
