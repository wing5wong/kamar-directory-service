<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Wing5wong\KamarDirectoryServices\DirectoryService\PastoralData;
use Wing5wong\KamarDirectoryServices\Models\Pastoral;

class ProcessPastorals implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private PastoralData $pastoralEntry
    ) {
        $this->onQueue('pastorals');
    }

    public function handle(): void
    {
        Pastoral::updateOrCreate(
            ['ref' => $this->pastoralEntry->ref],
            $this->pastoralEntry->toArray()
        );
    }
}
