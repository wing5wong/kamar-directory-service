<?php

namespace App\Listeners;

use App\Jobs\ProcessNotices;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Wing5wong\KamarDirectoryServices\Events\NoticeDataReceived;

class HandleNoticeDataReceived
{
    public function handle(NoticeDataReceived $event): void
    {
        ProcessNotices::dispatch($event->records);
    }
}
