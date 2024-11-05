<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class prepareStudentCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:prepare-student-csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all files in the data directory for management system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $time = new \DateTime();
        $allFiles = collect(Storage::files('data'));

        $students = $allFiles->filter(function ($fileName) {
            return str($fileName)->contains("full_");
        })
            ->sort()
            ->last();
        if ($students) {

            $cStudents = collect(data_get(json_decode(Storage::get($students), true), 'SMSDirectoryData.students.data'))
                ->map(function ($student) {
                    if (isset($student['passwordencrypted'])) {
                        $student['password'] = openssl_decrypt($student['passwordencrypted'], 'aes-128-ecb', config('kamar-directory-services.encryptionKey'));
                    }
                    return $student;
                });
            // ->mapWithKeys(function ($student) {
            //     return [$student["id"] => $student];
            // });
            Storage::put('processed/' . basename($students), json_encode($cStudents));
            Storage::delete($students);
        }
        $allFiles->filter(function ($fileName) {
            return str($fileName)->contains("part_");
        })
            ->each(function ($file) use ($time) {
                $this->info('Processing:' . $file);
                collect(data_get(json_decode(Storage::get($file), true), 'SMSDirectoryData.students.data'))
                    ->map(function ($student) {
                        if (isset($student['passwordencrypted'])) {
                            $student['password'] = openssl_decrypt($student['passwordencrypted'], 'aes-128-ecb', config('kamar-directory-services.encryptionKey'));
                        }
                        return $student;
                    })
                    ->whenNotEmpty(function ($mapped) use ($file, $time) {
                        Storage::put('processed/' . basename($file), json_encode($mapped));
                        Storage::delete($file);
                    });
            });
    }
}
