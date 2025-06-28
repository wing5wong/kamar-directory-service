<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Wing5wong\KamarDirectoryServices\DirectoryService\RecognitionData;
use Wing5wong\KamarDirectoryServices\Models\Recognition;

class ProcessRecognitions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private RecognitionData $recognition
    ) {
        $this->onQueue('recognitions');
    }

    public function handle(): void
    {
        Recognition::updateOrCreate(
            ['uuid' => $this->recognition->uuid],
            $this->recognition->toArray()
        );
    }
}
