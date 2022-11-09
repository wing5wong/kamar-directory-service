<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $this->assertTrue((KamarData::fromRequest())->isMissing());
    }

    public function test_isSyncCheck_returns_true_when_sync_is_check()
    {
        $this->setupSyncCheckRequest();
        $kamar = KamarData::fromRequest();
        $this->assertTrue($kamar->isSyncCheck());
    }

    public function test_isSyncCheck_returns_false_when_sync_is_not_check()
    {
        $this->setupSyncPartRequest();
        $kamar = KamarData::fromRequest();
        $this->assertFalse($kamar->isSyncCheck());
    }

    public function test_isSyncPart_returns_true_when_sync_is_part()
    {
        $this->setupSyncPartRequest();
        $kamar = KamarData::fromRequest();
        $this->assertTrue($kamar->isSyncPart());
    }

    public function test_isSyncPart_returns_false_when_sync_is_not_part()
    {
        $this->setupSyncCheckRequest();
        $kamar = KamarData::fromRequest();
        $this->assertFalse($kamar->isSyncPart());
    }

    public function test_it_creates_part_sync_from_file()
    {
        $kamar = KamarData::fromFile('tests/Unit/Stubs/partRequest.json',false);
        $this->assertTrue($kamar->isSyncPart());
    }

    public function test_it_creates_full_sync_from_file()
    {
        $kamar = KamarData::fromFile('tests/Unit/Stubs/fullRequest.json',false);
        $this->assertTrue($kamar->isSyncFull());
    }

    private function setupSyncCheckRequest()
    {
        $request = new Request();
        $request->merge(['SMSDirectoryData' => ['sync' => 'check']]);
        app()->instance('request', $request);
    }

    private function setupSyncPartRequest()
    {
        $request = new Request();
        $request->merge(['SMSDirectoryData' => ['sync' => 'part']]);
        app()->instance('request', $request);
    }

    private function setupEmptyRequest()
    {
        $request = new Request();
        $request->merge(['SMSDirectoryData' => []]);
        app()->instance('request', $request);
    }

    private function setupBlankRequest()
    {
        $request = new Request();
        app()->instance('request', $request);
    }

}
