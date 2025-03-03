<?php

namespace App\Listeners;

use App\Jobs\ProcessAttendance;
use App\Jobs\ProcessNotices;
use App\Jobs\ProcessPastorals;
use App\Jobs\ProcessStaff;
use App\Jobs\ProcessStudent;
use Illuminate\Support\Facades\Storage;
use Wing5wong\KamarDirectoryServices\Events\KamarPostStored;
use Wing5wong\KamarDirectoryServices\KamarData;

class ProcessKamarPost
{
    const STUDENT_DATA_PATH = 'SMSDirectoryData.students.data';

    const PASSWORD_ENCRYPTED = 'passwordencrypted';

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
            $this->processStudents($kamarData->getStudents());
            $this->processStaffMembers($kamarData->getStaff());


            // $deleted = Storage::disk(config('kamar-directory-services.storageDisk'))
            //     ->delete(config('kamar-directory-services.storageFolder') . '/' . $filename);
            // info("[finish] Deleted file: {$filename} [{$deleted}]");
        }

        if ($kamarData->isSyncType(KamarData::SYNC_TYPE_PASTORAL)) {
            info('process pastoral sync');
            $kamarData->getPastoral();
            ProcessPastorals::dispatch();
        }

        if ($kamarData->isSyncType(KamarData::SYNC_TYPE_ATTENDANCE)) {
            info('process attendance sync');
            ProcessAttendance::dispatch();
        }

        if ($kamarData->isSyncType(KamarData::SYNC_TYPE_NOTICES)) {
            info('process notices sync');
            ProcessNotices::dispatch($kamarData->getNotices());
        }
    }

    private function processStudent($student)
    {
        info('[start] Update account: ' . $student['id']);
        ProcessStudent::dispatch($student['id'], $student['username'], $student[self::PASSWORD_ENCRYPTED], $student['resetpassword'] ?? 0);
        info('[finish] Update account: ' . $student['id']);
    }

    private function processStaffMember($staff)
    {
        info('[start] Update account: ');
        ProcessStaff::dispatch();
        info('[finish] Update account: ');
    }

    private function processStudents($students)
    {
        info('[start] Processing students');
        $count = $students->count();
        $students->each(function ($student, $idx) use ($count) {
            info('Processing student ' . ($idx + 1) . " of {$count}");
            $this->processStudent($student);
        });
        info('[finish] Processing students');
    }

    private function processStaffMembers($staff)
    {
        info('[start] Processing staff');
        $count = $staff->count();
        $staff->each(function ($st, $idx) use ($count) {
            info('Processing staff ' . ($idx + 1) . " of {$count}");
            $this->processStaffMember($st);
        });
        info('[finish] Processing staff');
    }
}
