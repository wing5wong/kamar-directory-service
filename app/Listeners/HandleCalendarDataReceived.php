<?php

namespace App\Listeners;

use App\Jobs\ProcessCalendars;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Wing5wong\KamarDirectoryServices\Events\CalendarDataReceived;

class HandleCalendarDataReceived
{
    public function handle(CalendarDataReceived $event): void
    {
        ProcessCalendars::dispatch($event->records);
    }
}
