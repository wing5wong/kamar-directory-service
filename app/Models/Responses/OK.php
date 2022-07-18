<?php

namespace App\Http\Models\Responses;

use Illuminate\Contracts\Support\Arrayable;

class OK implements Arrayable
{
    public function toArray()
    {
        return [
            'SMSDirectoryData' => [
                'error' => 0,
                'result' => 'OK',
            ]
        ];
    }
}
