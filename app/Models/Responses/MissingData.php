<?php

namespace App\Models\Responses;

use Illuminate\Contracts\Support\Arrayable;

class MissingData implements Arrayable
{
    public function toArray()
    {
        return [
            'SMSDirectoryData' => [
                'error' => 401,
                'result' => 'No Data',
            ]
        ];
    }
}
