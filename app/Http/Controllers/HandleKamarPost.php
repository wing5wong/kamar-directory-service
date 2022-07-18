<?php

namespace App\Http\Controllers;

use App\Models\Responses\FailedAuthentication;
use App\Models\Responses\MissingData;
use App\Models\Responses\OK;
use App\Models\Responses\SyncCheck;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HandleKamarPost extends Controller
{
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->username = config('kamar.username');
        $this->password = config('kamar.password');
    }

    public function __invoke(Request $request)
    {
        // // Check supplied username/password  matches our expectation
        if ($this->authenticationFails()) {
            return $this->handleFailedAuthenticationResponse();
        }

        // // Check we have some data
        if ($this->isMissingData()) {
            return $this->handleMissingDataResponse();
        }

        // Check if a 'check' sync, return check response.
        if ($this->isSyncCheck()) {
            return $this->handleSyncCheckResponse();
        }

        // Check if a 'part' sync, return part response.
        if ($this->isSyncPart()) {
            return $this->handleOKResponse();
        }

        // All other messages - store the data and return 'OK' response.
        return $this->handleOKResponse();
    }

    private function authenticationFails()
    {
        return request()->server('HTTP_AUTHORIZATION') !== ("Basic " . base64_encode($this->username . ':' . $this->password));
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

    private function handleFailedAuthenticationResponse()
    {
        return response()->json(new FailedAuthentication());
    }

    private function handleMissingDataResponse()
    {
        return response()->json(new MissingData());
    }

    private function handleSyncCheckResponse()
    {
        return response()->json(new SyncCheck());
    }

    private function handleOKResponse()
    {
        // Do something
        $this->handleDirectoryData();
        return response()->json(new OK());
    }

    private function handleDirectoryData()
    {
        Storage::disk('local')
            ->put('data/' . time() . "_" . mt_rand(1000, 9999) . ".json", request()->getContent());
    }
}
