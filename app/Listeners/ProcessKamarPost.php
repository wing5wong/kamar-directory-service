<?php

namespace App\Listeners;

use App\Jobs\ProcessAttendance;
use App\Jobs\ProcessNotices;
use App\Jobs\ProcessPastorals;
use App\Jobs\ProcessStaff;
use App\Jobs\ProcessStudent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Wing5wong\KamarDirectoryServices\DirectoryService\AttendanceData;
use Wing5wong\KamarDirectoryServices\DirectoryService\PastoralData;
use Wing5wong\KamarDirectoryServices\DirectoryService\StaffData;
use Wing5wong\KamarDirectoryServices\DirectoryService\StudentData;
use Wing5wong\KamarDirectoryServices\Events\KamarPostStored;
use Wing5wong\KamarDirectoryServices\KamarData;

class ProcessKamarPost
{
    public function handle(KamarPostStored $event): void
    {
        info("[start] Processing file: {$event->filename}");
        $this->processDataFile($event->filename);
        info("[finish] Processing file: {$event->filename}");
    }

    private function processDataFile($filename)
    {
        $kamarData = KamarData::fromFile($filename);

        if ($kamarData->isSyncPart() || $kamarData->isSyncFull()) {
            $this->processMappedRecordDataOnQueue('students', ProcessStudent::class, $kamarData->getStudents(), StudentData::class);
            $this->processMappedRecordDataOnQueue('staff', ProcessStaff::class, $kamarData->getStaff(), StaffData::class);
        }

        if ($kamarData->isSyncType(KamarData::SYNC_TYPE_PASTORAL)) {
            $this->processMappedRecordDataOnQueue('pastorals', ProcessPastorals::class, $kamarData->getPastoral(), PastoralData::class);
        }

        if ($kamarData->isSyncType(KamarData::SYNC_TYPE_ATTENDANCE)) {
            $this->processMappedRecordDataOnQueue('attendances', ProcessAttendance::class, $kamarData->getAttendance(), AttendanceData::class);
        }

        if ($kamarData->isSyncType(KamarData::SYNC_TYPE_NOTICES)) {
            ProcessNotices::dispatch($kamarData->getNotices())->onQueue('notices');
        }

        $deleted = Storage::disk(config('kamar-directory-services.storageDisk'))
            ->delete(config('kamar-directory-services.storageFolder') . '/' . $filename);
        info("[finish] Deleted file: {$filename} [{$deleted}]");
    }

    private function processMappedRecordDataOnQueue(string $type, string $jobClass, Collection $records, string $dataClass): void
    {
        info("[start] Processing {$type} ({$records->count()} records)");

        $records->each(function ($record, $i) use ($type, $dataClass, $jobClass) {
            $dataObject = $dataClass::fromArray($record);
            $job = new $jobClass($dataObject);

            info("Dispatching {$type} #{$i}");
            dispatch($job)->onQueue($type);
        });

        info("[finish] Processing {$type}");
    }
}
