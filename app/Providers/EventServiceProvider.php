<?php

namespace App\Providers;

use App\Listeners\HandleAttendanceDataReceived;
use App\Listeners\HandleNoticeDataReceived;
use App\Listeners\HandlePastoralDataReceived;
use App\Listeners\HandleRecognitionDataReceived;
use App\Listeners\HandleStaffDataReceived;
use App\Listeners\HandleStudentDataReceived;
use App\Listeners\ProcessKamarPost;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Wing5wong\KamarDirectoryServices\Events\AttendanceDataReceived;
use Wing5wong\KamarDirectoryServices\Events\KamarPostStored;
use Wing5wong\KamarDirectoryServices\Events\NoticeDataReceived;
use Wing5wong\KamarDirectoryServices\Events\PastoralDataReceived;
use Wing5wong\KamarDirectoryServices\Events\RecognitionDataReceived;
use Wing5wong\KamarDirectoryServices\Events\StaffDataReceived;
use Wing5wong\KamarDirectoryServices\Events\StudentDataReceived;

class EventServiceProvider extends ServiceProvider
{

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return true;
    }
}
