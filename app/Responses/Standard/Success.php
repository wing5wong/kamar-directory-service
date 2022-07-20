<?php

namespace App\Responses\Standard;

use App\Responses\AbstractResponse;

class Success extends AbstractResponse
{
    protected int $error = 0;
    protected string $result = "OK";
}
