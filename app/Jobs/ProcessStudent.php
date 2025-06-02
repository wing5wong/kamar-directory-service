<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Wing5wong\KamarDirectoryServices\DirectoryService\StudentData;
use Wing5wong\KamarDirectoryServices\Encryption\KamarEncryptionInterface;
use Wing5wong\KamarDirectoryServices\Models\Student;

class ProcessStudent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $logChannel = 'processStudent';

    public function __construct(
        public StudentData $student
    ) {}

    public function handle(KamarEncryptionInterface $encrypter): void
    {
        info('[start] Update account: ' . $this->student->student_id);
        Log::channel($this->logChannel)->info("GOT JOB: {$this->student->student_id} {$this->student->username} {$this->student->passwordencrypted} {$this->student->resetpassword} ");

        try {
            Student::updateOrCreate(
                ['uuid' => $this->student->uuid],
                $this->student->toArray()
            );

            $password = $encrypter->decrypt($this->student->passwordencrypted);
            $path = base_path("\scripts\processStudent.ps1");
            $sId = $this->student->student_id;
            $sUsername = $this->student->username;
            $sReset = $this->student->resetpassword ? 1 : 0;

            $command = "powershell.exe -noprofile -NonInteractive -Command \"& '$path' -id '$sId' -username '$sUsername' -password '$password' -reset $sReset\"";

            Log::channel($this->logChannel)->info("[start] Run update script: " . $command);
            $output = shell_exec($command);
            Log::channel($this->logChannel)->info($output);
            Log::channel($this->logChannel)->info("[finish] Run update script");
        } catch (Exception $e) {
            Log::channel($this->logChannel)->warning("[error] Failed to update account");
            Log::channel($this->logChannel)->warning($e);
        } finally {
            info('[finish] Update account: ' . $this->student->student_id);
            return;
        }
    }
}
