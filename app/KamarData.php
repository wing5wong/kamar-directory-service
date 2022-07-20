<?php

namespace App;

class KamarData
{

    public function isMissing()
    {
        return empty(request()->getContent());
    }

    public function isSyncCheck()
    {
        return request('SMSDirectoryData.sync') === "check";
    }

    public function isSyncPart()
    {
        return request('SMSDirectoryData.sync') === "part";
    }

}
