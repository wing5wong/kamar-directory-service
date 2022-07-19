<?php

namespace App\Models\Responses;

class OK extends AbstractResponse
{
    protected int $error = 0;
    protected string $result = "OK";
}
