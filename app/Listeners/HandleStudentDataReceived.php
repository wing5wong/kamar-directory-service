<?php

namespace App\Listeners;

use App\Jobs\ProcessStudent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Wing5wong\KamarDirectoryServices\DirectoryService\StudentData;
use Wing5wong\KamarDirectoryServices\Events\StudentDataReceived;

class HandleStudentDataReceived
{
    public function handle(StudentDataReceived $event): void
    {
        $event->records->each(function ($record, $i) {
            info("Dispatching student #{$i}");
            dispatch(new ProcessStudent(StudentData::fromArray($record)));
        });
    }
}
