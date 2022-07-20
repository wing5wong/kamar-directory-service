<?php

namespace App\Http\Controllers;

use App\Responses\Check\{Success as CheckSuccess, FailedAuthentication as CheckFailedAuthentication, MissingData as CheckMissingData};
use App\Responses\Standard\{Success, FailedAuthentication, MissingData};
use App\{AuthenticationCheck, KamarData};

use Illuminate\Http\Request;

class HandleKamarPost extends Controller
{

    public function __construct(
        protected AuthenticationCheck $authCheck,
        protected KamarData $data,
    ) {
    }

    public function __invoke(Request $request)
    {
        // Check supplied username/password  matches our expectation
        if ($this->data->isSyncCheck()) {

            if ($this->authCheck->fails()) {
                return response()->json(new CheckFailedAuthentication());  // failed during check, we have to return error, code, version and service
            }

            if ($this->data->isMissing()) {
                return response()->json(new CheckMissingData());
            }

            return response()->json(new CheckSuccess());
        }

        if ($this->authCheck->fails()) {
            return response()->json(new FailedAuthentication());  // failed during any other request, we only have to return error and code
        }

        // Check we have some data
        if ($this->data->isMissing()) {
            return response()->json(new MissingData());
        }

        // Check if a 'part' sync, return part response.
        if ($this->data->isSyncPart()) {
            return $this->handleOKResponse();
        }

        // All other messages - store the data and return 'OK' response.
        return $this->handleOKResponse();
    }

    private function handleOKResponse()
    {
        // Do something
        $this->data->store();
        return response()->json(new Success());
    }
}
