<?php

namespace Wing5wong\KamarDirectoryService;

use Exception;
use Illuminate\Support\Facades\Storage;

class KamarData
{
    const SYNC_TYPE_CHECK = 'check';
    const SYNC_TYPE_PART = 'part';
    const SYNC_TYPE_FULL = 'full';
    const SYNC_TYPE_ASSESSMENTS ='assessments';
    const SYNC_TYPE_ATTENDANCE ='attendance';
    const SYNC_TYPE_BOOKINGS ='bookings';
    const SYNC_TYPE_CALENDAR ='calendar';
    const SYNC_TYPE_NOTICES ='notices';
    const SYNC_TYPE_PASTORAL ='pastoral';
    const SYNC_TYPE_PHOTOS ='photos';
    const SYNC_TYPE_STAFFPHOTOS ='staffphotos';
    const SYNC_TYPE_STUDENTTIMETABLES ='studenttimetables';
    const SYNC_TYPE_STAFFTIMETABLES ='stafftimetables';

    public $data;
    public $syncType;
    public $format = 'json';

    public function isMissing()
    {
        return $this->format == 'json'
        ? empty(data_get($this->data, 'SMSDirectoryData'))
        : $this->data->isEmpty();
    }

    public function getSyncType()
    {
        return $this->format == 'json'
        ? data_get($this->data,  'SMSDirectoryData.sync')
        : data_get($this->data,  '@attributes.sync');
    }

    public function isSyncType($syncType)
    {
        return $this->getSyncType() === $syncType; 
    }

    public function isSyncCheck()
    {
        return $this->isSyncType(self::SYNC_TYPE_CHECK);
    }

    public function isSyncPart()
    {
        return $this->isSyncType(self::SYNC_TYPE_PART);
    }

    public function isSyncFull()
    {
        return $this->isSyncType(self::SYNC_TYPE_FULL);
    }

    public function store()
    {
        $filename = $this->getSyncType() . "_" . time() . "_" . mt_rand(1000, 9999) . "." . $this->format;
        Storage::put('data/' . $filename, request()->getContent());
    }

    public function isJson()
    {
        return $this->format === 'json';
    }

    public function isXml()
    {
        return $this->format === 'xml';
    }

    public static function fromRequest()
    {
        $kamarData = new static;

        if (request()->isJson()) {
            $kamarData->setData(collect(request()->input()), 'json');
        }
        elseif (request()->isXml()) {
            $data = request()->getContent() > ''
            ? collect( request()->xml(true))
            : collect([]);
            $kamarData->setData($data, 'xml');
        }
        else {
            throw new Exception("Invalid content");
        }
        return $kamarData;
    }

    public static function fromFile($filename, $useBasePath = true)
    {
        $kamarData = new static;
        if ($useBasePath) {
            $kamarData->setData(collect(json_decode(Storage::disk('local')->get('data/' . $filename), true)), 'json');
        } else {
            $kamarData->setData(collect(json_decode(file_get_contents($filename), true)) , 'json');
        }
        return $kamarData;
    }

    public function getStaff()
    {
        return collect(data_get($this->data, 'SMSDirectoryData.staff.data'));
    }

    public function getStudents()
    {
        return collect(data_get($this->data, 'SMSDirectoryData.students.data'));
    }

    public function getStaffTimetables()
    {
        return $this->syncType == self::SYNC_TYPE_STAFFTIMETABLES
        ? collect(data_get($this->data, 'SMSDirectoryData.timetables.data'))
        : collect([]);
    }

    public function getStudentTimetables()
    {
        return $this->syncType == self::SYNC_TYPE_STUDENTTIMETABLES
        ? collect(data_get($this->data, 'SMSDirectoryData.timetables.data'))
        : collect([]);
    }

    public function getAttendance()
    {
        return data_get($this->data, 'SMSDirectoryData.attendance.data');
    }

    public function getPastoral()
    {
        return data_get($this->data, 'SMSDirectoryData.pastoral.data');
    }

    public function getResults()
    {
        return data_get($this->data, 'SMSDirectoryData.results.data');
    }

    private function setData($data, $format)
    {
        $this->data = $data;
        $this->format = $format;
        $this->syncType = $this->getSyncType();

        return $this;
    }
}
