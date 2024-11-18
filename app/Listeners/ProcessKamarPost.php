<?php

namespace App\Listeners;

use App\Jobs\ProcessStudent;
use Illuminate\Support\Facades\Storage;
use Wing5wong\KamarDirectoryServices\Events\KamarPostStored;

class ProcessKamarPost
{
    const STUDENT_DATA_PATH = "SMSDirectoryData.students.data";
    const PASSWORD_ENCRYPTED = "passwordencrypted";

    public function handle(KamarPostStored $event): void
    {
        info("[start] Processing file: {$event->filename}");
        $this->processDataFile($event->filename);
        info("[finish] Processing file: {$event->filename}");
    }

    private function processDataFile($filename)
    {
        $file = Storage::disk(config('kamar-directory-services.storageDisk'))
            ->get(config('kamar-directory-services.storageFolder') . '/' . $filename);

        info("[start] Processing students");
        $students = collect(
            data_get(json_decode($file, true), self::STUDENT_DATA_PATH)
        );

        $count = $students->count();

        $students->each(function ($student, $idx) use ($count) {
            info("Processing student " .  ($idx + 1) . " of {$count}");
            $this->processStudent($student);
        });

        info("[finish] Processing students");

        $deleted = Storage::disk(config('kamar-directory-services.storageDisk'))
            ->delete(config('kamar-directory-services.storageFolder') . '/' . $filename);
        info("[finish] Deleted file: {$filename} [{$deleted}]");
    }

    private function processStudent($student)
    {
        info("[start] Update account: " . $student["id"]);
        ProcessStudent::dispatch($student["id"], $student["username"], $student[self::PASSWORD_ENCRYPTED], $student['resetpassword'] ?? 0);
        info("[finish] Update account: " . $student["id"]);
    }
}
