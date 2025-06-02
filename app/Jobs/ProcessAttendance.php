<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Wing5wong\KamarDirectoryServices\DirectoryService\AttendanceData;
use Wing5wong\KamarDirectoryServices\Models\Attendance;

class ProcessAttendance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private AttendanceData $attendanceData
    ) {}

    public function handle(): void
    {
        // Transform the data into a flat array
        $records = collect($this->attendanceData->values)->map(function ($day) {
            info($this->attendanceData->studentId . " - " . Carbon::parse($day->date)->toDateString() . " - " . $day->codes);
            return [
                'student_id' => $this->attendanceData->studentId,
                'date' => Carbon::parse($day->date)->toDateString(),
                'nsn' => $this->attendanceData->nsn,
                'codes' => $day->codes,
                'alt' => $day->alt,
                'hdu' => $day->hdu,
                'hdp' => $day->hdp,
                'hdj' => $day->hdj,
            ];
        })->all();

        // Perform the bulk upsert
        Attendance::upsert(
            $records,
            ['student_id', 'date'], // Unique constraint columns
            ['nsn', 'codes', 'alt', 'hdu', 'hdp', 'hdj'] // Columns to update if a match is found
        );
        // collect($this->attendanceData->values)->each(function ($day) {
        //     info($this->attendanceData->studentId . " - " . \Carbon\Carbon::parse($day->date)->toDateString() . " - " . $day->codes);
        //     Attendance::updateOrCreate(
        //         [
        //             'student_id' => $this->attendanceData->studentId,
        //             'date' => \Carbon\Carbon::parse($day->date)->toDateString()
        //         ],
        //         [
        //             'nsn' => $this->attendanceData->nsn,
        //             'codes' => $day->codes,
        //             'alt' => $day->alt,
        //             'hdu' => $day->hdu,
        //             'hdp' => $day->hdp,
        //             'hdj' => $day->hdj
        //         ]
        //     );
        // });
    }
}
