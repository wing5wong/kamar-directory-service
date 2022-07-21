<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Http\Request;
use App\KamarData;

class KamarDataTest extends TestCase
{
    public function test_missing_data_no_SMSDirectoryData()
    {
        $request = new Request();
        app()->instance('request', $request);

        $this->assertTrue((new KamarData())->isMissing());
    }

    public function test_missing_data_empty_array()
    {
        $request = new Request();
        $request->replace(['SMSDirectoryData' => []]);
        app()->instance('request', $request);
        $this->assertFalse((new KamarData())->isMissing());
    }

    public function test_sync_check()
    {
        $request = new Request();
        $request->replace(['SMSDirectoryData' => ['sync' => 'check']]);
        app()->instance('request', $request);
        $this->assertTrue((new KamarData())->isSyncCheck());
    }

    public function test_sync_check_false_for_non_check()
    {
        $request = new Request();
        $request->replace(['SMSDirectoryData' => ['sync' => 'part']]);
        app()->instance('request', $request);
        $this->assertFalse((new KamarData())->isSyncCheck());
    }

    public function test_sync_part()
    {
        $request = new Request();
        $request->replace(['SMSDirectoryData' => ['sync' => 'part']]);
        app()->instance('request', $request);
        $this->assertTrue((new KamarData())->isSyncPart());
    }

    public function test_sync_part_false_for_non_part()
    {
        $request = new Request();
        $request->replace(['SMSDirectoryData' => ['sync' => 'check']]);
        app()->instance('request', $request);
        $this->assertFalse((new KamarData())->isSyncPart());
    }


}
