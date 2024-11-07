<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Wing5wong\KamarDirectoryServices\Encryption\KamarEncryptionInterface;

class prepareStudentCSV extends Command
{
    const PART_PREFIX = "part_";
    const FULL_PREFIX = "full_";
    const STUDENT_DATA_PATH = "SMSDirectoryData.students.data";
    const PASSWORD = "password";
    const PASSWORD_ENCRYPTED = "passwordencrypted";

    protected $encrypter;

    public function __construct(KamarEncryptionInterface $encrypter)
    {
        parent::__construct();
        $this->encrypter = $encrypter;
    }

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
        $this->processFiles();
    }

    private function processFiles()
    {
        $allFiles = collect(
            Storage::disk(config('kamar-directory-services.storageDisk'))
                ->files(config('kamar-directory-services.storageFolder'))
        );
        $this->processPartFiles($allFiles);
        $this->processMostRecentFullFile($allFiles);
    }

    private function processPartFiles($allFiles)
    {
        $allFiles->filter(function ($fileName) {
            return str($fileName)->contains(self::PART_PREFIX);
        })->each(function ($file) {
            $this->processDataFile($file);
        });
    }

    private function processMostRecentFullFile($allFiles)
    {
        $file = $allFiles->filter(function ($fileName) {
            return str($fileName)->contains(self::FULL_PREFIX);
        })->sort()->last();

        if ($file) {
            $this->processDataFile($file);
        }
    }

    private function processDataFile($file)
    {
        $data = $this->collectStudentsWithDecryptedPassword($file);
        if ($data->isNotEmpty()) {
            $this->storeProcessedDataAndDeleteOriginal($file, $data);
        }
    }

    private function collectStudentsWithDecryptedPassword($file): Collection
    {
        return collect(
            data_get(json_decode(Storage::get($file), true), self::STUDENT_DATA_PATH)
        )->map(function ($student) {
            return $this->setDecryptedPassword($student);
        });
    }

    private function setDecryptedPassword($student)
    {
        if (isset($student[self::PASSWORD_ENCRYPTED])) {
            $student[self::PASSWORD] = $this->encrypter->decrypt($student[self::PASSWORD_ENCRYPTED]);
        }
        return $student;
    }

    private function storeProcessedDataAndDeleteOriginal($file, $data)
    {
        Storage::put(
            config("app.processedDataDirectory") . '/' . basename($file),
            json_encode($data)
        );
        Storage::delete($file);
    }
}
