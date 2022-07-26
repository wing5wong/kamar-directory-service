<?php

namespace App;

use Illuminate\Support\Facades\Storage;

class KamarData
{

    public function isMissing()
    {
        return !request()->has('SMSDirectoryData');
    }

    public function getSyncType()
    {
        return request('SMSDirectoryData.sync');
    }

    public function isSyncCheck()
    {
        return $this->getSyncType() === "check";
    }

    public function isSyncPart()
    {
        return $this->getSyncType() === "part";
    }

    public function store()
    {
        Storage::disk('local')
            ->put('data/' . $this->getSyncType() . "_" . time() . "_" . mt_rand(1000, 9999) . ".json", request()->getContent());
    }

}
