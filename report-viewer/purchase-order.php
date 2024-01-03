<?php
require_once('../include/config.php');
include '../include/function-update.php';

$CompanyInfo = GetCompanyInfo($link);
$Products = GetProducts($link);
$Units = GetUnit($link);

$po_number = isset($_GET['po_number']) && $_GET['po_number'] !== '' ? $_GET['po_number'] : null;

// Check if the required parameter is not set or has an empty value
if ($po_number === null) {
    die("Invalid request. Please provide the 'po_number' parameter with a non-empty value.");
}

// Rest of your code goes here...

$PurchaseOrder = GetPurchaseOrderByID($link, $po_number);
$PurchaseOrderItems = GetPurchaseOrderItems($link, $po_number);

if (empty($PurchaseOrder)) {
    die("Invalid PO Number");
}

$pageTitle = "Purchase Order - " . $po_number;
$reportTitle = "Purchase Order";

$taxAmount = $shippingAmount = $otherAmount = 0;

$VendorID = $PurchaseOrder['supplier_id'];
$Supplier = GetSupplier($link)[$VendorID];


$OrderDate = $PurchaseOrder['created_at'];
$dateTime = new DateTime($OrderDate);
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
                <h2 class="report-title-mini"><?= strtoupper($reportTitle) ?></h2>
                <table>
                    <tr>
                        <th>Date</th>
                        <td class="text-end"><?= $formattedDate ?></td>
                    </tr>
                    <tr>
                        <th>PO Number</th>
                        <td class="text-end"><?= strtoupper($po_number) ?></td>
                    </tr>
                </table>
            </div>

        </div>
        <div id="container" class="section-2">
            <div id="left-section">
                <h3 class="sub-title">Vendor</h3>
                <p class="text-bold-extra"><?= $Supplier['supplier_name'] ?></p>
                <p><?= $Supplier['street_name'] ?>, <?= $Supplier['city'] ?>, <?= $Supplier['zip_code'] ?></p>
                <p>Tel: <?= $Supplier['supplier_name'] ?></p>
                <p>Email: <?= $Supplier['email'] ?></p>
                <p>Fax: <?= $Supplier['fax'] ?></p>
            </div>

            <div id="right-section">
                <h3 class="sub-title">Delivery to</h3>
                <p class="text-bold-extra"><?= $CompanyInfo['company_name'] ?></p>
                <p><?= $CompanyInfo['company_address'] ?>, <?= $CompanyInfo['company_address2'] ?></p>
                <p><?= $CompanyInfo['company_city'] ?>, <?= $CompanyInfo['company_postalcode'] ?></p>
                <p>Tel: <?= $CompanyInfo['company_telephone'] ?>/ <?= $CompanyInfo['company_telephone2'] ?></p>

            </div>
        </div>

        <div id="container" class="section-3">
            <table>
                <thead>
                    <tr>
                        <th>Requisitioner</th>
                        <th>Ship Via</th>
                        <th>F. O. B</th>
                        <th>Shipping Terms</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $PurchaseOrder['created_by'] ?></td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="container" class="section-4">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Description</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Item Start -->
                    <?php
                    if (!empty($PurchaseOrderItems)) {
                        $SubTotal = $rawNumber = 0;
                        foreach ($PurchaseOrderItems as $selectedArray) {

                            $OrderDate = $selectedArray['order_rate'];
                            $OrderQuantity = $selectedArray['quantity'];
                            $PerRate = $selectedArray['order_rate'];
                            $OrderUnit = $selectedArray['order_unit'];
                            $ProductID = $selectedArray['product_id'];

                            $productName = $Products[$ProductID]['product_name'];

                            $lineTotal = $PerRate * $OrderQuantity;
                            $rawNumber++;
                            $SubTotal += $lineTotal;
                    ?>
                            <tr>
                                <td class="text-center"><?= $rawNumber ?></td>
                                <td><?= MakeFormatProductCode($ProductID) ?> - <?= $productName ?></td>
                                <td class="text-center"><?= $OrderUnit ?></td>
                                <td class="text-center"><?= number_format($OrderQuantity, 2) ?></td>
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
                        <td class="text-bold no-border border-top">Sub Total</td>
                        <td class="text-end footer-border total border-top"><?= number_format($SubTotal, 2) ?></td>
                    </tr>



                    <tr>
                        <td colspan="4" rowspan="5" class="align-top no-border">
                            <?= $PurchaseOrder['remarks'] ?>
                        </td>
                        <td class="no-border text-bold">Tax</td>
                        <td class="text-end footer-border "><?= number_format($taxAmount, 2) ?></td>
                    </tr>

                    <tr>
                        <td class="text-bold no-border ">Shipping</td>
                        <td class="text-end footer-border "><?= number_format($shippingAmount, 2) ?></td>
                    </tr>

                    <tr>
                        <td class="text-bold no-border ">Other</td>
                        <td class="text-end footer-border "><?= number_format($otherAmount, 2) ?></td>
                    </tr>

                    <?php
                    // Calculate the Total Amount
                    $TotalAmount = $SubTotal + $taxAmount + $shippingAmount + $otherAmount;
                    ?>
                    <tr>
                        <td class="text-bold-extra no-border border-top-double">Total</td>
                        <td class="text-bold-extra  text-end footer-border border-top-double total"><?= number_format($TotalAmount, 2) ?></td>
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