<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KamarData
{
    public $data;

    public function isMissing()
    {
        return empty(data_get($this->data,'SMSDirectoryData'));
    }

    public function getSyncType()
    {
        return data_get($this->data, 'SMSDirectoryData.sync');
    }

    public function isSyncCheck()
    {
        return $this->getSyncType() === "check";
    }

    public function isSyncPart()
    {
        return $this->getSyncType() === "part";
    }

    public function isSyncFull()
    {
        return $this->getSyncType() === "full";
    }

    public function store()
    {
        $filename = $this->getSyncType() . "_" . time() . "_" . mt_rand(1000, 9999) . ".json";
        Storage::put('data/' . $filename, request()->getContent());
    }

    public static function fromRequest()
    {
        $kamarData = new static;
        $kamarData->data = collect(request()->input());
        return $kamarData;
    }

    public static function fromFile($filename, $useBasePath=true)
    {        
        $kamarData = new static;
        if($useBasePath) {
            $kamarData->data = collect(json_decode(Storage::disk('local')->get('data/' . $filename), true));
        }
        else {
            $kamarData->data = collect(json_decode(file_get_contents($filename), true));
        }
        return $kamarData;
    }

}
