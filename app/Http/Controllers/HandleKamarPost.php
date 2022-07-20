<?php

namespace App\Http\Controllers;

use App\Responses\Check\{Success as CheckSuccess, FailedAuthentication as CheckFailedAuthentication};
use App\Responses\Standard\{Success, MissingData};
use App\AuthenticationCheck;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class HandleKamarPost extends Controller
{

    public function __construct(
        protected AuthenticationCheck $authCheck
    ) {
    }

    public function __invoke(Request $request)
    {
        // Check supplied username/password  matches our expectation
        if ($this->authCheck->fails()) {
            return response()->json(new CheckFailedAuthentication());
        }

        // Check we have some data
        if ($this->isMissingData()) {
            return response()->json(new MissingData());
        }

        // Check if a 'check' sync, return check response.
        if ($this->isSyncCheck()) {
            return response()->json(new CheckSuccess());
        }

        // Check if a 'part' sync, return part response.
        if ($this->isSyncPart()) {
            return $this->handleOKResponse();
        }

        // All other messages - store the data and return 'OK' response.
        return $this->handleOKResponse();
    }

    private function isMissingData()
    {
        return empty(request()->getContent());
    }

    private function isSyncCheck()
    {
        return request('SMSDirectoryData.sync') === "check";
    }

    private function isSyncPart()
    {
        return request('SMSDirectoryData.sync') === "part";
    }

    private function handleOKResponse()
    {
        // Do something
        $this->handleDirectoryData();
        return response()->json(new Success());
    }

    private function handleDirectoryData()
    {
        Storage::disk('local')
            ->put('data/' . time() . "_" . mt_rand(1000, 9999) . ".json", request()->getContent());
    }
}
