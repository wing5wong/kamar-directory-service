<?php

namespace App\Responses;

use Illuminate\Contracts\Support\Arrayable;

abstract class AbstractResponse implements Arrayable
{
    protected int $error = 501;
    protected string $result = "Not Implemented";
    protected array $additionalFields = [];

    public function toArray()
    {
        return [
            'SMSDirectoryData' => array_merge(
                [
                    'error' => $this->error,
                    'result' => $this->result,
                ],
                $this->additionalFields
            )
        ];
    }
}
