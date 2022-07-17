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
            return $this->failedAuthenticationResponse();
        }

        // // Check we have some data
        if($this->isMissingData()) {
            return $this->missingDataResponse();
        }

        // Check if a 'check' sync, return check response.
        if ($this->isSyncCheck()) {
            return $this->syncCheckResponse();
        }

        // Check if a 'part' sync, return part response.
        if ($this->isSyncPart()) {
            return $this->OKResponse();
        }

        // All other messages - store the data and return 'OK' response.
        return $this->OKResponse();
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

    private function failedAuthenticationResponse()
    {
        return response()->json([
            "SMSDirectoryData" => [
                "error" => 403,
                "result" => "Authentication Failed",

                "service" => $this->service,
                "version" => $this->version,
            ]
        ]);
    }

    private function missingDataResponse()
    {
        return response()->json([
            "SMSDirectoryData" => [
                "error" => 401,
                "result" => "No Data",

                "service" => $this->service,
                "version" => $this->version,
            ]
        ]);
    }

    private function syncCheckResponse()
    {
        return response()->json([
            'SMSDirectoryData' => [
                'error' => 0,
                'result' => 'OK',
                "status" => "Ready",

                "service" => $this->service,
                "version" =>  $this->version,

                "infourl" => $this->infoUrl,
                "privacystatement" => $this->privacyStatement,

                "options" => $this->options,
            ]
        ]);
    }

    private function OKResponse()
    {
        // Do something
        Storage::disk('local')
                ->put('data/'.time()."_".mt_rand(1000,9999).".json", request()->getContent());

        return response()->json([
            'SMSDirectoryData' => [
                'error' => 0,
                'result' => 'OK',
            ]
        ]);
    }
}
