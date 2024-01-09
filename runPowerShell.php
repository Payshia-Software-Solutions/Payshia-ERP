<?php
header('Content-Type: application/json');

// Execute PowerShell command to get the list of printers
exec('powershell.exe -Command "Get-Printer | Select-Object Name"', $output, $returnCode);

// Check if the command was successful
if ($returnCode === 0) {
    // Extract printer names from the output
    $printers = array_map('trim', $output);
    echo json_encode(['success' => true, 'printers' => $printers]);
} else {
    echo json_encode(['success' => false, 'output' => implode("\n", $output)]);
}
