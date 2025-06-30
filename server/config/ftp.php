<?php

// FTP configuration settings
return [
    'ftp_server'   => 'ftp.pharmacollege.lk', // Replace with your FTP server address
    'ftp_username' => 'server@pharmacollege.lk',  // Replace with your FTP username
    'ftp_password' => 'I6Fr}l)S3e!e',  // Replace with your FTP password
    'ftp_port'     => 21 // FTP port
];

// $ftp_server = $ftpConfig['ftp_server'];
// $ftp_username = $ftpConfig['ftp_username'];
// $ftp_password = $ftpConfig['ftp_password'];
// $ftp_port = $ftpConfig['ftp_port'];
// $ftp_target_dir = '/content-provider/certificates/e-certificate/test/';

// // Attempt to connect to the FTP server with port
// $ftp_conn = ftp_connect($ftp_server);
// if (!$ftp_conn) {
//     die("Could not connect to FTP server: $ftp_server on port $ftp_port");
// }

// // Attempt to login to the FTP server
// $login = ftp_login($ftp_conn, $ftp_username, $ftp_password);
// if (!$login) {
//     ftp_close($ftp_conn);
//     die("Could not login to FTP server with provided credentials.");
// }


// // Function to recursively create directories
// function ftp_mkdir_recursive($ftp_conn, $dir)
// {
//     $dirArray = explode('/', $dir);
//     $path = '';

//     foreach ($dirArray as $directory) {
//         if (empty($directory)) continue;

//         $path .= '/' . $directory;
//         if (!@ftp_chdir($ftp_conn, $path)) {
//             if (ftp_mkdir($ftp_conn, $path)) {
//                 echo "Created directory: $path\n";
//             } else {
//                 return false;
//             }
//         }
//     }
//     return true;
// }

// // Create the target directory
// if (ftp_mkdir_recursive($ftp_conn, $ftp_target_dir)) {
//     echo "Directory structure created successfully.\n";
// } else {
//     echo "Failed to create directory structure.\n";
// }

// // Create a test file
// $local_file = 'test_file.txt';
// $file_content = "This is a test file to check FTP connection.";
// file_put_contents($local_file, $file_content);

// // Attempt to upload the file
// $upload = ftp_put($ftp_conn, $ftp_target_dir . 'test_file.txt', $local_file, FTP_ASCII);
// if ($upload) {
//     echo "File uploaded successfully to $ftp_target_dir\n";
// } else {
//     echo "Failed to upload the file.\n";
// }

// // Clean up by deleting the test file
// unlink($local_file);

// // Close the FTP connection
// ftp_close($ftp_conn);
