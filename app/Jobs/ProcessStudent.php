<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Wing5wong\KamarDirectoryServices\Encryption\KamarEncryptionInterface;

class ProcessStudent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public $studentId,
        public $username,
        public $password,
        public $reset
    ) {}

    /**
     * Execute the job.
     */
    public function handle(KamarEncryptionInterface $encrypter): void
    {
        Log::channel('processStudent')->info("GOT JOB: {$this->studentId} {$this->username} {$this->password} {$this->reset} ");

        try {
            $password = $encrypter->decrypt($this->password);
            $path = base_path("\scripts\processStudent.ps1");
            $sId = $this->studentId;
            $sUsername = $this->username;
            $sReset = $this->reset;

            $command = "powershell.exe -noprofile -NonInteractive -Command \"& '$path' -id '$sId' -username '$sUsername' -password '$password' -reset $sReset\"";

            Log::channel('processStudent')->info("[start] Run update script: " . $command);
            $output = shell_exec($command);
            Log::channel('processStudent')->info($output);
            Log::channel('processStudent')->info("[finish] Run update script");

            Log::channel('processStudent')->info("[finish] Update account: " . $this->studentId);
        } catch (Exception $e) {
            Log::channel('processStudent')->warning("[error] Failed to update account");
            Log::channel('processStudent')->warning($e);
        } finally {
            return;
        }
    }
}
