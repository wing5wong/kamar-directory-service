<?php

namespace App\Responses\Standard;

use App\Responses\AbstractResponse;

class MissingData extends AbstractResponse
{
    protected int $error = 400;
    protected string $result = "Bad Request";
}
