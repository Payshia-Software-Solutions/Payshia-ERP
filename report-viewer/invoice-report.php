<?php
require_once('../include/config.php');
include '../include/function-update.php';
include '../include/finance-functions.php';
include '../include/reporting-functions.php';

$fromQueryDate = isset($_GET['fromQueryDate']) && $_GET['fromQueryDate'] !== '' ? $_GET['fromQueryDate'] : null;
$toQueryDate = isset($_GET['toQueryDate']) && $_GET['toQueryDate'] !== '' ? $_GET['toQueryDate'] : null;
$location_id = isset($_GET['location_id']) && $_GET['location_id'] !== '' ? $_GET['location_id'] : null;

// Check if the required parameters are not set or have empty values
if ($fromQueryDate === null || $toQueryDate === null || $location_id === null) {
    die("Invalid request. Please provide all required parameters with non-empty values.");
}

// Rest of your code goes here...


$Locations = GetLocations($link);
$CompanyInfo = GetCompanyInfo($link);
$Products = GetProducts($link);
$Units = GetUnit($link);

$LocationID = $location_id;
$fromDate = new DateTime($fromQueryDate);
$formattedFromQueryDate = $fromDate->format('d/m/Y');


$toDate = new DateTime($toQueryDate);
$formattedToQueryDate = $toDate->format('d/m/Y');

$generateDAte = new DateTime();
$reportDate = $generateDAte->format('d/m/Y H:i:s');
$LocationName = $Locations[$LocationID]['location_name'];

$pageTitle = "Invoice Report - " . $fromQueryDate . " - " . $toQueryDate;
$reportTitle = "Invoice Report";

$subTotal = $discountAmount = $serviceCharge = $grandTotal  = 0;

$invoiceSales = getInvoicesByDateRangeAll($link, $fromQueryDate, $toQueryDate, $location_id);
$location_name = $Locations[$location_id]['location_name'];
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
    <style>
        @media print {
            .page-break {
                page-break-inside: avoid;
            }

        }
    </style>

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
                <h4 class="report-title-mini"><?= strtoupper($reportTitle) ?></h4>
                <table>
                    <tr>
                        <th>From Date</th>
                        <td class="text-end"><?= $formattedFromQueryDate ?></td>
                    </tr>
                    <tr>
                        <th>To Date</th>
                        <td class="text-end"><?= $formattedToQueryDate ?></td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td class="text-end"><?= $location_name ?></td>
                    </tr>
                </table>
            </div>

        </div>



        <p style="font-weight:600;margin-top:10px; margin-bottom:0px">Report is generated on <?= $reportDate ?></p>
        <div id="" class="">
            <?php
            if (!empty($invoiceSales)) {
                foreach ($invoiceSales as $selectedArray) {
                    $referenceText = "";

                    $taxAmount = $shippingAmount = $otherAmount = 0;
                    $invoice_date = date("Y-m-d H:i", strtotime($selectedArray['current_time']));
                    $subTotal += $selectedArray['inv_amount'];
                    $ref_hold = $selectedArray['ref_hold'];
                    $discountAmount += $selectedArray['discount_amount'];
                    $serviceCharge += $selectedArray['service_charge'];
                    $grandTotal += $selectedArray['grand_total'];




                    $SelectedInvoice = $selectedArray;
                    $CustomerID = $SelectedInvoice['customer_code'];
                    $Customer = GetCustomersByID($link, $CustomerID);

                    $invoice_number = $selectedArray['invoice_number'];

                    $InvoiceDate = $SelectedInvoice['current_time'];
                    $dateTime = new DateTime($InvoiceDate);
                    $formattedDate = $dateTime->format('d/m/Y H:i:s');


                    $LocationName = $Locations[$SelectedInvoice['location_id']]['location_name'];

                    $InvProducts = GetInvoiceItems($link, $invoice_number);
                    $discountPercentage = $SelectedInvoice['discount_percentage'];
                    $discountAmount = $SelectedInvoice['discount_amount'];

                    $ref_hold = $SelectedInvoice['ref_hold'];
                    if ($ref_hold == '0') {
                        // $referenceText = "Take Away";
                        $referenceText = "Direct";
                    } else if ($ref_hold == '-1') {
                        // $referenceText = "Retail";
                        $referenceText = "Direct";
                    } else if ($ref_hold == '-2') {
                        // $referenceText = "Delivery";
                        $referenceText = "Direct";
                    } else if ($ref_hold == "") {
                        // $referenceText = "None";
                        $referenceText = "Direct";
                    } else {
                        $referenceText = $ref_hold;
                    }

            ?>

                    <div class="invoice page-break">
                        <div id="container">

                            <div id="left-section" style="width: 50% !important;">

                                <table style="float: left !important;">

                                    <tr>
                                        <th class="text-start">INV Number</th>
                                        <td class="text-start"><?= strtoupper($invoice_number) ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-start">Date</th>
                                        <td class="text-start"><?= $formattedDate ?></td>
                                    </tr>

                                    <tr>
                                        <th class="text-start">Customer</th>
                                        <td class="text-start"><?= $Customer['customer_first_name'] ?> <?= $Customer['customer_last_name'] ?></td>
                                    </tr>

                                </table>
                            </div>

                            <div id="right-section" style="width: 50% !important;">

                                <table style="float: left !important;">
                                    <tr>
                                        <th class="text-start">Location</th>
                                        <td class="text-start"><?= $LocationName ?></td>
                                    </tr>

                                    <tr>
                                        <th class="text-start">Ref Number</th>
                                        <td class="text-start"><?= strtoupper($referenceText) ?></td>
                                    </tr>

                                </table>
                            </div>

                        </div>

                        <div id="container" class="section-4">
                            <table style="margin-top: 5px;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Description</th>
                                        <th>Unit</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Discount</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Item Start -->
                                    <?php
                                    if (!empty($InvProducts)) {
                                        $SubTotal = $rawNumber = 0;
                                        foreach ($InvProducts as $selectedArray) {

                                            $OrderDate = $selectedArray['added_date'];
                                            $OrderQuantity = $selectedArray['quantity'];
                                            $PerRate = $selectedArray['item_price'];
                                            $ProductID = $selectedArray['product_id'];
                                            $item_discount = $selectedArray['item_discount'];
                                            $OrderUnit = $Units[$Products[$ProductID]['measurement']]['unit_name'];


                                            $productName = $Products[$ProductID]['product_name'];

                                            $lineTotal = ($PerRate - $item_discount) * $OrderQuantity;
                                            $rawNumber++;
                                            $SubTotal += $lineTotal;
                                    ?>
                                            <tr>
                                                <td class="text-center"><?= $rawNumber ?></td>
                                                <td><?= MakeFormatProductCode($ProductID) ?> - <?= $productName ?></td>
                                                <td class="text-center"><?= $OrderUnit ?></td>
                                                <td class="text-center"><?= number_format($OrderQuantity, 2) ?></td>
                                                <td class="text-end"><?= number_format($PerRate, 2) ?></td>
                                                <td class="text-end"><?= number_format($item_discount, 2) ?></td>
                                                <td class="text-end total"><?= number_format($lineTotal, 2) ?></td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>



                                    <!-- End of the Items -->
                                    <tr>
                                        <td colspan="5" class="align-top  border-top add-theme">
                                            Comments & Special Instructions
                                        </td>
                                        <td class="text-bold no-border border-top">Sub Total</td>
                                        <td class="text-end footer-border total border-top"><?= number_format($SubTotal, 2) ?></td>
                                    </tr>


                                    <tr>
                                        <td colspan="5" rowspan="5" class="align-top no-border">
                                            <?= $SelectedInvoice['remark'] ?>
                                        </td>
                                        <td class="no-border text-bold">Discount (<?= number_format($discountPercentage, 2) ?>%)</td>
                                        <td class="text-end footer-border "><?= number_format($discountAmount, 2) ?></td>
                                    </tr>

                                    <tr>
                                        <td class="text-bold no-border ">Charge</td>
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
                                    $TotalAmount = ($SubTotal - $discountAmount) + $taxAmount + $shippingAmount + $otherAmount;
                                    ?>
                                    <tr>
                                        <td class="text-bold-extra no-border border-top-double">Total</td>
                                        <td class="text-bold-extra  text-end footer-border border-top-double total"><?= number_format($TotalAmount, 2) ?></td>
                                    </tr>
                                </tbody>
                            </table>


                        </div>
                    </div>


            <?php
                }
            }
            ?>
        </div>

        <script>
            // window.print();

            // // Close the window after printing
            // window.onafterprint = function() {
            //     window.close();
            // };
        </script>
    </div>

</body>

</html>