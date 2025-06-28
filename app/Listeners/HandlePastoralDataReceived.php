<?php

namespace App\Listeners;

use App\Jobs\ProcessPastorals;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Wing5wong\KamarDirectoryServices\DirectoryService\PastoralData;
use Wing5wong\KamarDirectoryServices\Events\PastoralDataReceived;

class HandlePastoralDataReceived
{
    public function handle(PastoralDataReceived $event): void
    {
        $event->records->each(function ($record, $i) {
            info("Dispatching pastoral #{$i}");
            dispatch(new ProcessPastorals(PastoralData::fromArray($record)));
        });
    }
}
