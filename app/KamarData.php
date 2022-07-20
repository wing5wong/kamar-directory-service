<?php

namespace App;

use Illuminate\Support\Facades\Storage;

class KamarData
{

    public function isMissing()
    {
        return !request()->has('SMSDirectoryData');
    }

    public function isSyncCheck()
    {
        return request('SMSDirectoryData.sync') === "check";
    }

    public function isSyncPart()
    {
        return request('SMSDirectoryData.sync') === "part";
    }

    public function store()
    {
        Storage::disk('local')
            ->put('data/' . time() . "_" . mt_rand(1000, 9999) . ".json", request()->getContent());
    }

}
