<?php

namespace App\Models\Responses;

use Illuminate\Contracts\Support\Arrayable;

abstract class AbstractResponse implements Arrayable
{
    protected int $error;
    protected string $result;

    public function toArray()
    {
        return [
            'SMSDirectoryData' => [
                'error' => $this->error,
                'result' => $this->result,
            ]
        ];
    }
}
