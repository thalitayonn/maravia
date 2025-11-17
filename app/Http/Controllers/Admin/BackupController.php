<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use ZipArchive;

class BackupController extends Controller
{
    public function __construct()
    {
        // Middleware handled by route group
    }

    public function index()
    {
        $backups = $this->getBackupFiles();
        return view('admin.backup.index', compact('backups'));
    }

    public function create(Request $request)
    {
        try {
            $backupName = 'backup_' . date('Y-m-d_H-i-s');
            $backupPath = storage_path('app/backups');
            
            // Create backup directory if it doesn't exist
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            // Database backup
            $dbBackupFile = $backupPath . '/' . $backupName . '_database.sql';
            $this->createDatabaseBackup($dbBackupFile);

            // Files backup
            $filesBackupFile = $backupPath . '/' . $backupName . '_files.zip';
            $this->createFilesBackup($filesBackupFile);

            // Create combined backup
            $combinedBackupFile = $backupPath . '/' . $backupName . '_complete.zip';
            $this->createCombinedBackup($combinedBackupFile, $dbBackupFile, $filesBackupFile);

            // Clean up individual files
            unlink($dbBackupFile);
            unlink($filesBackupFile);

            return response()->json([
                'success' => true,
                'message' => 'Backup created successfully!',
                'filename' => $backupName . '_complete.zip'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create backup: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download($filename)
    {
        $filePath = storage_path('app/backups/' . $filename);
        
        if (!file_exists($filePath)) {
            abort(404, 'Backup file not found');
        }

        return response()->download($filePath);
    }

    public function delete($filename)
    {
        try {
            $filePath = storage_path('app/backups/' . $filename);
            
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            return response()->json([
                'success' => true,
                'message' => 'Backup deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete backup: ' . $e->getMessage()
            ], 500);
        }
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:zip'
        ]);

        try {
            $uploadedFile = $request->file('backup_file');
            $tempPath = storage_path('app/temp');
            
            // Create temp directory
            if (!file_exists($tempPath)) {
                mkdir($tempPath, 0755, true);
            }

            // Extract backup
            $zip = new ZipArchive;
            $extractPath = $tempPath . '/restore_' . time();
            
            if ($zip->open($uploadedFile->getPathname()) === TRUE) {
                $zip->extractTo($extractPath);
                $zip->close();
            } else {
                throw new \Exception('Failed to extract backup file');
            }

            // Restore database
            $sqlFiles = glob($extractPath . '/*.sql');
            if (!empty($sqlFiles)) {
                $this->restoreDatabase($sqlFiles[0]);
            }

            // Restore files
            $this->restoreFiles($extractPath);

            // Clean up
            $this->deleteDirectory($extractPath);

            return response()->json([
                'success' => true,
                'message' => 'Backup restored successfully! Please clear cache and refresh the application.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore backup: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getBackupFiles()
    {
        $backupPath = storage_path('app/backups');
        $backups = [];

        if (is_dir($backupPath)) {
            $files = glob($backupPath . '/*.zip');
            
            foreach ($files as $file) {
                $backups[] = [
                    'filename' => basename($file),
                    'size' => $this->formatBytes(filesize($file)),
                    'created_at' => date('Y-m-d H:i:s', filemtime($file))
                ];
            }

            // Sort by creation time (newest first)
            usort($backups, function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
        }

        return $backups;
    }

    private function createDatabaseBackup($filename)
    {
        $connection = config('database.default');
        $database = config("database.connections.{$connection}.database");
        $username = config("database.connections.{$connection}.username");
        $password = config("database.connections.{$connection}.password");
        $host = config("database.connections.{$connection}.host");
        $port = config("database.connections.{$connection}.port");

        if ($connection === 'sqlite') {
            // For SQLite, just copy the database file
            $dbPath = config("database.connections.{$connection}.database");
            copy($dbPath, $filename);
        } else {
            // For MySQL/PostgreSQL
            $command = "mysqldump --user={$username} --password={$password} --host={$host} --port={$port} {$database} > {$filename}";
            exec($command);
        }
    }

    private function createFilesBackup($filename)
    {
        $zip = new ZipArchive;
        
        if ($zip->open($filename, ZipArchive::CREATE) === TRUE) {
            // Add storage files
            $this->addDirectoryToZip($zip, storage_path('app/public'), 'storage');
            
            // Add uploaded photos
            $this->addDirectoryToZip($zip, public_path('storage'), 'public_storage');
            
            $zip->close();
        }
    }

    private function createCombinedBackup($filename, $dbFile, $filesFile)
    {
        $zip = new ZipArchive;
        
        if ($zip->open($filename, ZipArchive::CREATE) === TRUE) {
            $zip->addFile($dbFile, 'database.sql');
            
            // Extract and add files from files backup
            $filesZip = new ZipArchive;
            if ($filesZip->open($filesFile) === TRUE) {
                for ($i = 0; $i < $filesZip->numFiles; $i++) {
                    $fileInfo = $filesZip->statIndex($i);
                    $content = $filesZip->getFromIndex($i);
                    $zip->addFromString($fileInfo['name'], $content);
                }
                $filesZip->close();
            }
            
            $zip->close();
        }
    }

    private function addDirectoryToZip($zip, $dir, $zipDir = '')
    {
        if (!is_dir($dir)) return;
        
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = $zipDir . '/' . substr($filePath, strlen($dir) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
    }

    private function restoreDatabase($sqlFile)
    {
        $connection = config('database.default');
        
        if ($connection === 'sqlite') {
            $dbPath = config("database.connections.{$connection}.database");
            copy($sqlFile, $dbPath);
        } else {
            $database = config("database.connections.{$connection}.database");
            $username = config("database.connections.{$connection}.username");
            $password = config("database.connections.{$connection}.password");
            $host = config("database.connections.{$connection}.host");
            $port = config("database.connections.{$connection}.port");
            
            $command = "mysql --user={$username} --password={$password} --host={$host} --port={$port} {$database} < {$sqlFile}";
            exec($command);
        }
    }

    private function restoreFiles($extractPath)
    {
        // Restore storage files
        $storageSource = $extractPath . '/storage';
        if (is_dir($storageSource)) {
            $this->copyDirectory($storageSource, storage_path('app/public'));
        }

        // Restore public storage files
        $publicStorageSource = $extractPath . '/public_storage';
        if (is_dir($publicStorageSource)) {
            $this->copyDirectory($publicStorageSource, public_path('storage'));
        }
    }

    private function copyDirectory($src, $dst)
    {
        if (!is_dir($dst)) {
            mkdir($dst, 0755, true);
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($src),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($files as $file) {
            $targetPath = $dst . '/' . substr($file, strlen($src) + 1);
            
            if ($file->isDir()) {
                if (!is_dir($targetPath)) {
                    mkdir($targetPath, 0755, true);
                }
            } else {
                copy($file, $targetPath);
            }
        }
    }

    private function deleteDirectory($dir)
    {
        if (!is_dir($dir)) return;
        
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        
        rmdir($dir);
    }

    private function formatBytes($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        
        return round($size, $precision) . ' ' . $units[$i];
    }
}
