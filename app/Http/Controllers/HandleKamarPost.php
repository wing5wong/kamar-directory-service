<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HandleKamarPost extends Controller
{
    public $service;
    public $version;
    public $infoUrl;
    public $privacyStatement;
    public $options;
    private $username;
    private $password;

    public function __construct()
    {
        $this->service = config('kamar.serviceName');
        $this->version = config('kamar.serviceVersion');
        $this->infoUrl = config('kamar.infoUrl');
        $this->privacyStatement = config('kamar.privacyStatement');
        $this->options = config('kamar.options');
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
        return $this->sendResponse(403, 'Authentication Failed', true);
    }

    private function handleMissingDataResponse()
    {
        return $this->sendResponse(401, 'No Data');
    }

    private function handleSyncCheckResponse()
    {
        return $this->sendResponse(0, 'OK', true, true);
    }

    private function handleOKResponse()
    {
        // Do something
        $this->handleDirectoryData();
        return $this->sendResponse(0, 'OK');
    }

    private function sendResponse($error, $result, $includeServiceDetail = false, $includeCheckData = false)
    {
        $directoryData =  [
            'error' => $error,
            'result' => $result,
        ];

        if ($includeServiceDetail) {
            $directoryData = array_merge($directoryData, [
                "service" => $this->service,
                "version" =>  $this->version,
            ]);
        }

        if ($includeCheckData) {
            $directoryData = array_merge($directoryData, [
                "status" => "Ready",
                "infourl" => $this->infoUrl,
                "privacystatement" => $this->privacyStatement,
                "options" => $this->options,
            ]);
        }

        return response()->json([
            'SMSDirectoryData' => $directoryData
        ]);
    }

    private function handleDirectoryData()
    {
        Storage::disk('local')
            ->put('data/' . time() . "_" . mt_rand(1000, 9999) . ".json", request()->getContent());
    }
}
