<?php

namespace App\Listeners;

use App\Jobs\ProcessAttendance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Wing5wong\KamarDirectoryServices\DirectoryService\AttendanceData;
use Wing5wong\KamarDirectoryServices\Events\AttendanceDataReceived;

class HandleAttendanceDataReceived
{
    public function handle(AttendanceDataReceived $event): void
    {
        $event->records->each(function ($record, $i) {
            info("Dispatching attendance #{$i}");
            dispatch(new ProcessAttendance(AttendanceData::fromArray($record)));
        });
    }
}
