<?php
// Your database connection details
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = ''; // Replace with your actual root password
$dbName = 'uni_erp';

// Full path to mysqldump binary
$mysqlDumpPath = 'C:\wamp64x86\bin\mysql\mysql8.2.0\bin\mysqldump.exe';

// Generate a unique filename for the backup
$backupFileName = 'backup_' . date('Ymd_His') . '.sql';

// Build the mysqldump command
$command = "$mysqlDumpPath -h$dbHost -u$dbUser -p$dbPass $dbName > $backupFileName 2>&1";

// Execute the command and capture the output
$output = [];
exec($command, $output, $result);

// Output the command and result for debugging
echo "Command: $command<br>";
echo "Result: $result<br>";
echo "Output: <pre>" . implode("\n", $output) . "</pre>";

// Provide a download link for the backup file
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $backupFileName . '"');
readfile($backupFileName);

// Delete the backup file (optional)
unlink($backupFileName);
