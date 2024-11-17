<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../../vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;

// Handle file upload
$image = $_FILES['product_image'];

if ($image['error'] === UPLOAD_ERR_OK) {
    $imagePath = $image['tmp_name'];
    $originalFileName = $image['name'];

    $client = HttpClient::create();

    try {
        $response = $client->request('POST', 'https://kduserver.payshia.com/product-images', [
            'headers' => [
                'Accept' => 'application/json', // Expect JSON response
            ],
            'body' => [
                'product_id' => $_POST['productId'],    // Product ID
                'is_active' => 1,                       // Active status
                'created_by' => $_POST['LoggedUser'],   // Created by
                'created_at' => date('Y-m-d H:i:s'),    // Current timestamp
                'original_filename' => $originalFileName,
                'image' => fopen($imagePath, 'r'),      // Include the image file
            ],
        ]);

        // Handle server response
        $statusCode = $response->getStatusCode();
        if ($statusCode === 201) {
            echo json_encode(['status' => 'success', 'message' => 'Image and data uploaded successfully.']);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to upload image. Server response: ' . $response->getContent(false)
            ]);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Request failed: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Image upload error.']);
}
