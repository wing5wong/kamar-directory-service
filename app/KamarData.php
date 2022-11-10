<?php

namespace App;

use Exception;
use Illuminate\Support\Facades\Storage;

class KamarData
{
    public $data;
    public $format = 'json';

    public function isMissing()
    {
        return empty(data_get($this->data, 'SMSDirectoryData'));
    }

    public function getSyncType()
    {
        return data_get($this->data, $this->format == 'json' ? 'SMSDirectoryData.sync' : '@attributes.sync');
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
        $filename = $this->getSyncType() . "_" . time() . "_" . mt_rand(1000, 9999) . $this->format;
        Storage::put('data/' . $filename, request()->getContent());
    }

    public function isJson()
    {
        return $this->format === 'json';
    }

    public function isXml()
    {
        return $this->format === 'xml';
    }

    public static function fromRequest()
    {
        $kamarData = new static;
        if (request()->isJson()) {
            $kamarData->data = collect(request()->input());
        } elseif (request()->isXml()) {
            $kamarData->data = collect(request()->xml(true));
            $kamarData->format = 'xml';
        } else {
            throw new Exception("Invalid content");
        }
        return $kamarData;
    }

    public static function fromFile($filename, $useBasePath = true)
    {
        $kamarData = new static;
        if ($useBasePath) {
            $kamarData->data = collect(json_decode(Storage::disk('local')->get('data/' . $filename), true));
        } else {
            $kamarData->data = collect(json_decode(file_get_contents($filename), true));
        }
        return $kamarData;
    }
}
