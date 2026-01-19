<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Carbon\Carbon;
use Exception;

class SystemSettingService
{
    /**
     * Get all system settings as key-value pairs.
     *
     * @return array
     */
    public function getAllSettings(): array
    {
        return SystemSetting::all()->pluck('setting_value', 'setting_key')->toArray();
    }

    /**
     * Bulk update or create system settings.
     *
     * @param array $settings
     * @return Collection
     */
    public function updateSettings(array $settings): Collection
    {
        $updatedSettings = collect();

        foreach ($settings as $key => $value) {
            $setting = SystemSetting::updateOrCreate(
                ['setting_key' => $key],
                ['setting_value' => $value]
            );
            $updatedSettings->push($setting);
        }

        return $updatedSettings;
    }

    /**
     * Create a database backup.
     *
     * @return string Relative path to the backup file
     * @throws Exception
     */
    public function backupDatabase(): string
    {
        $connection = config('database.default');

        if ($connection !== 'pgsql') {
            throw new Exception("Backup is currently only supported for PostgreSQL.");
        }

        $config = config("database.connections.{$connection}");
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $filename = "backup-{$timestamp}.dump";
        $directory = 'backups';

        // Ensure directory exists in storage/app/backups
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        $absolutePath = Storage::path("{$directory}/{$filename}");

        // Prepare environment variables for the process
        $env = [
            'PGPASSWORD' => $config['password'],
        ];

        // Command: pg_dump -h host -p port -U user -F c -b -v -f file dbname
        $command = [
            'pg_dump',
            '-h',
            $config['host'],
            '-p',
            $config['port'],
            '-U',
            $config['username'],
            '-F',
            'c', // Custom format (compressed, suitable for pg_restore)
            '-b',      // Include large objects (blobs)
            '-v',      // Verbose
            '-f',
            $absolutePath,
            $config['database']
        ];

        $process = new Process($command, null, $env);
        $process->setTimeout(300); // 5 minutes timeout
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return "{$directory}/{$filename}";
    }

    /**
     * Restore database from backup file.
     *
     * @param string $filePath Absolute path to the backup file
     * @throws Exception
     */
    public function restoreDatabase(string $filePath): void
    {
        $connection = config('database.default');

        if ($connection !== 'pgsql') {
            throw new Exception("Restore is currently only supported for PostgreSQL.");
        }

        $config = config("database.connections.{$connection}");

        if (!file_exists($filePath)) {
            throw new Exception("Backup file not found.");
        }

        // Prepare environment variables
        $env = [
            'PGPASSWORD' => $config['password'],
        ];

        // Command: pg_restore -h host -p port -U user -d dbname -v -c file
        $command = [
            'pg_restore',
            '-h',
            $config['host'],
            '-p',
            $config['port'],
            '-U',
            $config['username'],
            '-d',
            $config['database'],
            '-v',
            '-c', // Clean (drop) database objects before creating
            $filePath
        ];

        $process = new Process($command, null, $env);
        $process->setTimeout(300); // 5 minutes timeout
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
