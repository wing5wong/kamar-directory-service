<?php

namespace Wing5wong\KamarDirectoryService\Controllers;

use Illuminate\Routing\Controller;
use Wing5wong\KamarDirectoryService\KamarData;
use Wing5wong\KamarDirectoryService\Auth\AuthenticationCheck;
use Wing5wong\KamarDirectoryService\Responses\Check\Success as CheckSuccess;
use Wing5wong\KamarDirectoryService\Responses\Check\XMLSuccess as XMLCheckSuccess;
use Wing5wong\KamarDirectoryService\Responses\Standard\{Success, FailedAuthentication, MissingData};
use Wing5wong\KamarDirectoryService\Responses\Standard\{XMLSuccess, XMLFailedAuthentication, XMLMissingData};

class HandleKamarPost extends Controller
{

    public function __construct(
        protected AuthenticationCheck $authCheck,
        protected KamarData $data,
    ) {
        $this->data = KamarData::fromRequest();
    }

    public function __invoke()
    {
        // Check supplied username/password matches our expectation
        if ($this->authCheck->fails()) {
            if ($this->data->isJson()) {
                return response()->json(new FailedAuthentication());
            }
            if ($this->data->isXml()) {
                return response()->xml((string)(new XmlFailedAuthentication()));
            }
        }

        // Check we have some data
        if ($this->data->isMissing()) {
            if ($this->data->isJson()) {
                return response()->json(new MissingData());
            }
            if ($this->data->isXml()) {
                return response()->xml((string)(new XmlMissingData()));
            }
        }

        // Check if a 'check' sync, return check response.
        if ($this->data->isSyncCheck()) {
            if ($this->data->isJson()) {
                return response()->json(new CheckSuccess());
            }
            if ($this->data->isXml()) {
                return response()->xml((string)(new XmlCheckSuccess()));
            }
        }

        // All other messages - store the data and return 'OK' response.
        return $this->handleOKResponse();
    }

    private function handleOKResponse()
    {
        // Do something
        $this->data->store();
        if ($this->data->isJson()) {
            return response()->json(new Success());
        }
        if ($this->data->isXml()) {
            return response()->xml((string)(new XmlSuccess()));
        }
    }
}
