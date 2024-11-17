<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;

$productId = $_POST['productId'];
$client = HttpClient::create();

$serverUrl = "https://kduserver.payshia.com/";

$response = $client->request('GET', $serverUrl . 'product-images/get-by-product/' . $productId);
// Check if the response status code is 404
if ($response->getStatusCode() === 404) {
    // If 404, set $productImages as an empty array
    $productImages = [];
} else {
    // Otherwise, parse the response body as an array
    $productImages = $response->toArray();
}

?>

<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-dark" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3 class="mb-0">Product Images</h3>
            <p class="border-bottom pb-2">Please fill the all required fields.</p>

            <form id="product-image-form" method="post">
                <div class="row g-2">
                    <div class="col-md-8 mb-2">
                        <h6 class="taxi-label">Logo</h6>
                        <input type="file" class="form-control" id="product_image" name="product_image">
                    </div>

                    <div class="col-md-4 mb-2">
                        <h6 class="taxi-label">Action</h6>
                        <button class="btn btn-dark w-100 form-control" type="button" name="BookPackageButton" id="BookPackageButton" onclick="SaveProductImages (<?= $productId ?>)">Save</button>
                    </div>
                </div>

            </form>

            <div class="row">
                <?php foreach ($productImages as $product) : ?>
                    <div class="col-3">
                        <img class="w-100 rounded" src="<?= $serverUrl ?>/uploads/images/product-images/<?= $productId ?>/<?= $product['image_path'] ?>" alt="">
                        <?php if ($product['is_active'] == 1) : ?>
                            <button class="btn btn-dark w-100 mt-2" type="button" onclick="changeImageStatus('<?= $product['id'] ?>', 0, '<?= $productId ?>')">Inactive</button>
                        <?php else : ?>
                            <button class="btn btn-primary w-100 mt-2" type="button" onclick="changeImageStatus('<?= $product['id'] ?>', 1, '<?= $productId ?>')">Active</button>
                        <?php endif ?>

                    </div>
                <?php endforeach ?>

            </div>
            <!-- Test -->
        </div>
    </div>
</div>