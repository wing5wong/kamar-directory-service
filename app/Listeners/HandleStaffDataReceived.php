<?php

namespace App\Listeners;

use App\Jobs\ProcessStaff;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Wing5wong\KamarDirectoryServices\DirectoryService\StaffData;
use Wing5wong\KamarDirectoryServices\Events\StaffDataReceived;

class HandleStaffDataReceived
{
    public function handle(StaffDataReceived $event): void
    {
        $event->records->each(function ($record, $i) {
            info("Dispatching staff #{$i}");
            dispatch(new ProcessStaff(StaffData::fromArray($record)));
        });
    }
}
