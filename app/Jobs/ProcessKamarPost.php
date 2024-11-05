<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use Exception;

class ProcessKamarPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    public $filename;

    public function __construct(
        $filename
    ) {
        $this->filename = $filename;
    }

    public function handle()
    {
        //Log::info('Got Job:' . $this->filename);
        $this->data = collect(json_decode(Storage::disk('local')->get('data/' . $this->filename), true));
        if (in_array(data_get($this->data, 'SMSDirectoryData.sync'), ['full'])) {
            $this->updateStudentRecords();
            $this->updateStaffRecords();
        }
        if (!in_array(data_get($this->data, 'SMSDirectoryData.sync'), ['full'])) {
            Storage::disk('local')->delete('data/' . $this->filename);
        }
        //Log::info('Finished Job:' . $this->filename);
    }

    public function updateStudentRecords()
    {
        //Log::info('processing students');
        if (collect(data_get($this->data, 'SMSDirectoryData.students.data'))->isNotEmpty()) {
            foreach (collect(data_get($this->data, 'SMSDirectoryData.students.data')) as $student) {
                $updates = [
                    'name' => $student['firstname'] . " " . $student['lastname'],
                    'password' => bcrypt(base64_decode($student['password'])),
                    'email' => $student['username'] . '@whs.ac.nz',
                    // 'role' => 'Student'
                ];

                try {
                    User::updateOrCreate(
                        ['unique_id' => $student['uniqueid']],
                        $updates
                    );
                } catch (Exception $e) {
                    //Log::warning('Error creating user' . $student['firstname'] . ' ' . $student['lastname'] . "(" . $student['uniqueid'] . ")");
                    //Log::warning($e->getMessage());
                }
            }
        }
    }

    public function updateStaffRecords()
    {
        //Log::info('processing staff');
        if (collect(data_get($this->data, 'SMSDirectoryData.staff.data'))->isNotEmpty()) {
            foreach (collect(data_get($this->data, 'SMSDirectoryData.staff.data')) as $staff) {
                $updates = [
                    'name' => $staff['firstname'] . " " . $staff['lastname'],
                    'email' => $staff['username'] . '@whs.ac.nz',
                    //'role' => 'Staff'
                ];
                try {
                    User::updateOrCreate(
                        ['unique_id' => $staff['uniqueid']],
                        $updates
                    );
                } catch (Exception $e) {
                    //Log::warning('Error creating user' . $staff['firstname'] . ' ' . $staff['lastname'] . "(" . $staff['uniqueid'] . ")");
                    //Log::warning($e->getMessage());
                }
            }
        }
    }
}
