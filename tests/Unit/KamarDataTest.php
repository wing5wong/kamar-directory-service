<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Http\Request;
use App\KamarData;

class KamarDataTest extends TestCase
{
    public function test_isMissing_returns_true_when_request_is_blank()
    {
        $this->setupBlankRequest();
        $this->assertTrue((new KamarData())->isMissing());
    }

    public function test_isMissing_returns_true_when_request_SMSDirectoryData_is_empty_array()
    {
        $this->setupEmptyRequest();
        $this->assertFalse((new KamarData())->isMissing());
    }

    public function test_isSyncCheck_returns_true_when_sync_is_check()
    {
        $this->setupSyncCheckRequest();
        $this->assertTrue((new KamarData())->isSyncCheck());
    }

    public function test_isSyncCheck_returns_false_when_sync_is_not_check()
    {
        $this->setupSyncPartRequest();
        $this->assertFalse((new KamarData())->isSyncCheck());
    }

    public function test_isSyncPart_returns_true_when_sync_is_part()
    {
        $this->setupSyncPartRequest();
        $this->assertTrue((new KamarData())->isSyncPart());
    }

    public function test_isSyncPart_returns_false_when_sync_is_not_part()
    {
        $this->setupSyncCheckRequest();
        $this->assertFalse((new KamarData())->isSyncPart());
    }

    private function setupSyncCheckRequest()
    {
        $request = new Request();
        $request->replace(['SMSDirectoryData' => ['sync' => 'check']]);
        app()->instance('request', $request);
    }

    private function setupSyncPartRequest()
    {
        $request = new Request();
        $request->replace(['SMSDirectoryData' => ['sync' => 'part']]);
        app()->instance('request', $request);
    }

    private function setupEmptyRequest()
    {
        $request = new Request();
        $request->replace(['SMSDirectoryData' => []]);
        app()->instance('request', $request);
    }

    private function setupBlankRequest()
    {
        $request = new Request();
        app()->instance('request', $request);
    }

}
