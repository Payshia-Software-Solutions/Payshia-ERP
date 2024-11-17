<?php
require_once '../../../../vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;

$client = HttpClient::create();

// Gather necessary data from the form (or whatever source you're using)
$productId = $_POST['updateKey'];  // Product ID from the form
$isActive = $_POST['IsActive'];    // The new 'is_active' value (0 or 1)

try {
    // Send a PUT request to update the product image status
    $response = $client->request('PUT', 'https://kduserver.payshia.com/product-images/change-status/' . $productId, [
        'headers' => [
            'Accept' => 'application/json', // Expect JSON response
        ],
        'json' => [
            'is_active' => $isActive,   // Include the updated 'is_active' status
        ],
    ]);

    // Handle server response
    $statusCode = $response->getStatusCode();
    if ($statusCode === 200) {
        // If the request was successful
        echo json_encode(['status' => 'success', 'message' => 'Product status updated successfully.']);
    } else {
        // If the server responded with an error
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to update product status. Server response: ' . $response->getContent(false),
        ]);
    }
} catch (Exception $e) {
    // Handle any exceptions that occur during the request
    echo json_encode(['status' => 'error', 'message' => 'Request failed: ' . $e->getMessage()]);
}
