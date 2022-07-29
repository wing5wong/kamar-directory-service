<?php

namespace App;

use Exception;
use App\Jobs\ProcessKamarPost;
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
        if(!$this->data) { return false; }
        return $this->getSyncType() === "part";
    }

    public function store()
    {
        $filename = $this->getSyncType() . "_" . time() . "_" . mt_rand(1000, 9999) . ".json";
        Storage::put('data/' . $filename, request()->getContent());
        ProcessKamarPost::dispatch($filename);
    }

    public static function fromRequest()
    {
        $kamarData = new static;
        $kamarData->data = collect(request()->input());
        return $kamarData;
    }

    public static function fromFile($filename)
    {        
        $kamarData = new static;
        $kamarData->data = collect(json_decode(Storage::disk('local')->get('data/' . $filename), true));
        return $kamarData;
    }

}
