<?php
// Define the API URL
$apiUrl = 'http://localhost/jlk_travel/web-admin/API/mailer.php'; // Replace with the actual URL of your API

// Prepare the data to send
$data = [
    'to' => 'thilinaruwan112@gmail.com',
    'subject' => 'Test Email',
    'message' => 'This is a test email sent via the API.',
    'fromEmail' => 'no-reply@jlktours.com',
    'fromName' => 'Your Name'
];

// Convert data to JSON
$dataJson = json_encode($data);

// Initialize cURL session
$ch = curl_init($apiUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);

// Execute the cURL session
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
} else {
    // Print API response
    echo $response;
}

// Close cURL session
curl_close($ch);
