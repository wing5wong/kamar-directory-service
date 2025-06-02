<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Wing5wong\KamarDirectoryServices\DirectoryService\StaffData;
use Wing5wong\KamarDirectoryServices\Models\Staff;

class ProcessStaff implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $logChannel = 'processStaff';

    public function __construct(
        public StaffData $staff
    ) {}

    public function handle(): void
    {
        Log::channel($this->logChannel)->info('[start] Update account: ' . $this->staff->staff_id);

        try {
            $path = base_path("\scripts\processStaff.ps1");
            Staff::updateOrCreate(
                ['uuid' => $this->staff->uuid],
                $this->staff->toArray()
            );


            Log::channel($this->logChannel)->info($this->staff->getDepartmentGroups()->implode('name', ','));
            Log::channel($this->logChannel)->info($this->staff->getClassificationGroups()->implode('name', ','));
            $departments = $this->staff->getDepartmentGroups()->map(function ($item) {
                return "'{$item['name']}'";
            })->implode(',');

            $classifications = $this->staff->getClassificationGroups()->map(function ($item) {
                return "'{$item['name']}'";
            })->implode(',');

            // Build optional parameters
            $departmentPart = !empty($departments) ? "-departments $departments" : '';
            $classificationPart = !empty($classifications) ? "-classifications $classifications" : '';

            $additionalData = [
                'firstName' => $this->staff->firstname,
                'lastName' => $this->staff->lastname,
                'position' => $this->staff->position,
                'house' => $this->staff->house,
                'classification' => $this->staff->classification,
                'photocopierId' => $this->staff->photocopierid,
                'phone' => $this->staff->extension
            ];

            $json = json_encode($additionalData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $escapedJson = '"' . str_replace('"', '\"', $json) . '"';
            $escapedJson = "'" . str_replace("'", "''", $escapedJson) . "'";

            // Combine the full command
            $command = sprintf(
                'powershell.exe -noprofile -NonInteractive -Command "& %s -username %s %s %s -additionalData %s"',
                escapeshellarg($path),
                $this->staff->username,
                $departmentPart,
                $classificationPart,
                $escapedJson
            );


            Log::channel($this->logChannel)->info("[start] Run update script: " . $command);
            $output = shell_exec($command);
            Log::channel($this->logChannel)->info($output);
            Log::channel($this->logChannel)->info("[finish] Run update script");
        } catch (Exception $e) {
            Log::channel($this->logChannel)->warning("[error] Failed to update account");
            Log::channel($this->logChannel)->warning($e);
        } finally {
            Log::channel($this->logChannel)->info('[finish] Update account: ' . $this->staff->staff_id);
            return;
        }
    }
}
