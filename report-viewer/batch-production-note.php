<?php
require_once('../include/config.php');
include '../include/function-update.php';

$CompanyInfo = GetCompanyInfo($link);
$Products = GetProducts($link);
$Units = GetUnit($link);
$Locations = GetLocations($link);

$batchNumber = isset($_GET['batchNumber']) && $_GET['batchNumber'] !== '' ? $_GET['batchNumber'] : null;

// Check if the required parameter is not set or has an empty value
if ($batchNumber === null) {
    die("Invalid request. Please provide the 'batchNumber' parameter with a non-empty value.");
}

if (!isset(GetBatchProduction()[$batchNumber])) {
    die("Invalid request. Please provide the 'batchNumber' Correctly");
}

// Rest of your code goes here...

$selectedPN = GetBatchProduction()[$batchNumber];
$PNItems = GetBatchProductionItems($batchNumber);
$Units = GetUnit($link);

$ProductId = $selectedPN['batch_product'];


$unitId = $Products[$ProductId]['measurement'];
$unitProduct = $Units[$unitId]['unit_name'];

$remarks = $selectedPN['remarks'];
$costPerKgValue = $selectedPN['cost_per_kg'];
$productionQty = $selectedPN['production_qty'];
$location_id = $selectedPN['location_id'];
$targetQty = $selectedPN['target_qty'];
$excessLossQty = $productionQty - $targetQty;
$excessLossQtyText; // Declare the variable here

$locationName = $Locations[$location_id]['location_name'];

if ($excessLossQty > 0) {
    $excessLossQtyText = "Excess";
} elseif ($excessLossQty < 0) {
    $excessLossQtyText = "Loss";
} else {
    $excessLossQtyText = "Fine";
}

$pageTitle = "Production Sheet- " . $batchNumber;
$reportTitle = "Production Sheet";

$POdate = $selectedPN['update_at'];
$dateTime = new DateTime($POdate);
$formattedDate = $dateTime->format('d/m/Y H:i:s');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>

    <!-- Favicons -->
    <link href="../assets/images/favicon/apple-touch-icon.png" rel="icon">
    <link href="../assets/images/favicon/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/report-viewer.css">
</head>

<body>
    <div class="invoice">
        <div id="container">
            <div id="left-section">
                <h3 class="company-title"><?= $CompanyInfo['company_name'] ?></h3>
                <p><?= $CompanyInfo['company_address'] ?>, <?= $CompanyInfo['company_address2'] ?></p>
                <p><?= $CompanyInfo['company_city'] ?>, <?= $CompanyInfo['company_postalcode'] ?></p>
                <p>Tel: <?= $CompanyInfo['company_telephone'] ?>/ <?= $CompanyInfo['company_telephone2'] ?></p>
                <p>Email: <?= $CompanyInfo['company_email'] ?></p>
                <p>Web: <?= $CompanyInfo['website'] ?></p>
            </div>

            <div id="right-section">
                <h5 class="report-title-mini"><?= strtoupper($reportTitle) ?></h5>
                <table>
                    <tr>
                        <th>Date</th>
                        <td class="text-end"><?= $formattedDate ?></td>
                    </tr>
                    <tr>
                        <th>Batch Number</th>
                        <td class="text-end"><?= strtoupper($batchNumber) ?></td>
                    </tr>
                    <tr>
                        <th>Location Name</th>
                        <td class="text-end"><?= $locationName ?></td>
                    </tr>
                </table>
            </div>

        </div>
        <div id="container" class="section-3">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Cost/<?= $unitProduct ?></th>
                        <th>Target</th>
                        <th>Production</th>
                        <th>Excess</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $Products[$selectedPN['batch_product']]['product_name'] ?></td>
                        <td class="text-center"><?= $costPerKgValue ?></td>
                        <td class="text-center"><?= number_format($targetQty, 2) ?><?= $unitProduct ?></td>
                        <td class="text-center"><?= number_format($productionQty, 2) ?><?= $unitProduct ?></td>
                        <td class="text-center"><?= abs($excessLossQty); ?><?= $unitProduct ?> <?= $excessLossQtyText; ?></td>
                    </tr>
                    <tr>
                        <td colspan="5">Remarks : <?= $remarks ?></td>
                    </tr>
                </tbody>
            </table>
        </div>


        <div id="container" class="section-4">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th>Cost Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Item Start -->
                    <?php

                    $SubTotal = $rawNumber = 0;
                    if (!empty($PNItems)) {
                        foreach ($PNItems as $selectedArray) {

                            $OrderQuantity = $selectedArray['product_qty'];
                            $PerRate = $selectedArray['cost_price'];
                            $ProductID = $selectedArray['product_id'];
                            $OrderUnit = $Units[$Products[$ProductID]['measurement']]['unit_name'];
                            $productName = $Products[$ProductID]['product_name'];

                            $lineTotal = $PerRate * $OrderQuantity;
                            $rawNumber++;
                            $SubTotal += $lineTotal;
                    ?>
                            <tr>
                                <td class="text-center"><?= $rawNumber ?></td>
                                <td><?= $productName ?></td>
                                <td class="text-center"><?= number_format($OrderQuantity, 3) ?></td>
                                <td class="text-center"><?= $OrderUnit ?></td>
                                <td class="text-end"><?= number_format($PerRate, 2) ?></td>
                                <td class="text-end total"><?= number_format($lineTotal, 2) ?></td>
                            </tr>
                    <?php
                        }
                    }
                    ?>

                    <!-- End of the Items -->
                    <tr>
                        <td colspan="4" class="align-top  border-top add-theme">
                            Comments & Special Instructions
                        </td>
                        <td class="text-bold no-border text-bold-extra border-top">Total</td>
                        <td class="text-end footer-border text-bold-extra total border-top"><?= number_format($SubTotal, 2) ?></td>
                    </tr>

                </tbody>
            </table>


        </div>

        <div id="container" class="section-6" style="margin-top: 60px;">
            <div class="signature-box">
                <p>..................................</p>
                <p class="text-bold">Checked by</p>
            </div>
            <div class="signature-box">
                <p>..................................</p>
                <p class="text-bold">Authorized by</p>
            </div>
            <div class="signature-box">
                <p>..................................</p>
                <p class="text-bold">Received by</p>
            </div>
        </div>

        <script>
            window.print();

            // // Close the window after printing
            // window.onafterprint = function() {
            //     window.close();
            // };
        </script>
    </div>

</body>

</html>