<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use App\KamarData;
use Tests\TestCase;
use Illuminate\Http\Request;
use Spatie\ArrayToXml\ArrayToXml;

class KamarDataTest extends TestCase
{
    public function test_isMissing_returns_true_when_request_is_blank()
    {
        $this->setupBlankRequest();
        $this->assertTrue((new KamarData())->isMissing());
    }

    public function test_isMissing_returns_true_when_XMLrequest_is_blank()
    {
        $this->setupBlankXMLRequest();
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

    public function test_isSyncCheck_returns_true_when_XMLsync_is_check()
    {
        $this->setupSyncCheckXMLRequest();
        $kamar = KamarData::fromRequest();
        $this->assertTrue($kamar->isSyncCheck());
    }

    public function test_isSyncCheck_returns_false_when_sync_is_not_check()
    {
        $this->setupSyncPartRequest();
        $kamar = KamarData::fromRequest();
        $this->assertFalse($kamar->isSyncCheck());
    }

    public function test_isSyncCheck_returns_false_when_XMLsync_is_not_check()
    {
        $this->setupSyncPartXMLRequest();
        $kamar = KamarData::fromRequest();
        $this->assertFalse($kamar->isSyncCheck());
    }

    public function test_isSyncPart_returns_true_when_sync_is_part()
    {
        $this->setupSyncPartRequest();
        $kamar = KamarData::fromRequest();
        $this->assertTrue($kamar->isSyncPart());
    }

    public function test_isSyncPart_returns_true_when_XMLsync_is_part()
    {
        $this->setupSyncPartXMLRequest();
        $kamar = KamarData::fromRequest();
        $this->assertTrue($kamar->isSyncPart());
    }

    public function test_isSyncPart_returns_false_when_sync_is_not_part()
    {
        $this->setupSyncCheckRequest();
        $kamar = KamarData::fromRequest();
        $this->assertFalse($kamar->isSyncPart());
    }

    /**
    * @dataProvider syncTypeDataProvider
    */
    public function test_itGetsTheCorrectSyncType($syncConst, $syncType)
    {
        $this->setupGenericSyncRequest($syncType);
        $kamar = KamarData::fromRequest();
        $this->assertSame(constant("App\KamarData::$syncConst"), $kamar->getSyncType());
    }

    /**
    * @dataProvider syncTypeDataProvider
    */
    public function test_itGetsTheCorrectXMLSyncType($syncConst, $syncType)
    {
        $this->setupGenericXMLSyncRequest($syncType);
        $kamar = KamarData::fromRequest();
        $this->assertSame(constant("App\KamarData::$syncConst"), $kamar->getSyncType());
    }

    public function test_isSyncPart_returns_false_when_XMLsync_is_not_part()
    {
        $this->setupSyncCheckXMLRequest();
        $kamar = KamarData::fromRequest();
        $this->assertFalse($kamar->isSyncPart());
    }

    public function test_it_creates_part_sync_from_file()
    {
        $kamar = KamarData::fromFile('tests/Unit/Stubs/partRequest.json', false);
        $this->assertTrue($kamar->isSyncPart());
    }

    public function test_it_creates_full_sync_from_file()
    {
        $kamar = KamarData::fromFile('tests/Unit/Stubs/fullRequest.json', false);
        $this->assertTrue($kamar->isSyncFull());
    }

    private function setupSyncCheckRequest()
    {
        $request = new Request();
        $request->headers->set('content-type', 'application/json');
        $request->merge(['SMSDirectoryData' => ['sync' => 'check']]);
        app()->instance('request', $request);
    }

    private function setupSyncPartRequest()
    {
        $request = new Request();
        $request->headers->set('content-type', 'application/json');
        $request->merge(['SMSDirectoryData' => ['sync' => 'part']]);
        app()->instance('request', $request);
    }

    private function setupEmptyRequest()
    {
        $request = new Request();
        $request->headers->set('content-type', 'application/json');
        $request->merge(['SMSDirectoryData' => []]);
        app()->instance('request', $request);
    }

    private function setupBlankRequest()
    {
        $request = new Request();
        $request->headers->set('content-type', 'application/json');
        app()->instance('request', $request);
    }



    private function setupSyncCheckXMLRequest()
    {
        $request = new Request();
        $request = Request::create('/', 'POST', [], [], [], [], ArrayToXml::convert(['@attributes' => ['sync' => 'check']], 'SMSDirectoryData'));

        $request->headers->set('content-type', 'application/xml');
        app()->instance('request', $request);
    }

    private function setupSyncPartXMLRequest()
    {
        $request = Request::create('/', 'POST', [], [], [], [], ArrayToXml::convert(['@attributes' => ['sync' => 'part']], 'SMSDirectoryData'));
        $request->headers->set('content-type', 'application/xml');
        app()->instance('request', $request);
    }

    private function setupGenericXMLSyncRequest($syncType)
    {
        $request = Request::create('/', 'POST', [], [], [], [], ArrayToXml::convert(['@attributes' => ['sync' => $syncType]], 'SMSDirectoryData'));
        $request->headers->set('content-type', 'application/xml');
        app()->instance('request', $request);
    }

    private function setupGenericSyncRequest($syncType)
    {
        $request = new Request();
        $request->headers->set('content-type', 'application/json');
        $request->merge(['SMSDirectoryData' => ['sync' => $syncType]]);
        app()->instance('request', $request);
    }

    private function setupBlankXMLRequest()
    {
        $request = new Request();
        $request->headers->set('content-type', 'application/xml');
        app()->instance('request', $request);
    }

    public function syncTypeDataProvider()
    {
        return     [
        ['SYNC_TYPE_CHECK', 'check'],
        ['SYNC_TYPE_PART', 'part'],
        ['SYNC_TYPE_FULL', 'full'],
        ['SYNC_TYPE_ASSESSMENTS','assessments'],
        ['SYNC_TYPE_ATTENDANCE','attendance'],
        ['SYNC_TYPE_BOOKINGS','bookings'],
        ['SYNC_TYPE_CALENDAR','calendar'],
        ['SYNC_TYPE_NOTICES','notices'],
        ['SYNC_TYPE_PASTORAL','pastoral'],
        ['SYNC_TYPE_PHOTOS','photos'],
        ['SYNC_TYPE_STAFFPHOTOS','staffphotos'],
        ['SYNC_TYPE_STUDENTTIMETABLES','studenttimetables'],
        ['SYNC_TYPE_STAFFTIMETABLES','stafftimetables'],
        ];
    }
}
