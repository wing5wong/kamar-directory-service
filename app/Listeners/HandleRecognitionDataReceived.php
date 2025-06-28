<?php

namespace App\Listeners;

use App\Jobs\ProcessRecognitions;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Wing5wong\KamarDirectoryServices\DirectoryService\RecognitionData;
use Wing5wong\KamarDirectoryServices\Events\RecognitionDataReceived;

class HandleRecognitionDataReceived
{
    public function handle(RecognitionDataReceived $event): void
    {
        $event->records->each(function ($record, $i) {
            info("Dispatching recognition #{$i}");
            dispatch(new ProcessRecognitions(RecognitionData::fromArray($record)));
        });
    }
}
