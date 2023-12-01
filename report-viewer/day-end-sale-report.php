<?php
require_once('../include/config.php');
include '../include/function-update.php';
include '../include/finance-functions.php';
include '../include/reporting-functions.php';

$date = isset($_GET['date_input']) && $_GET['date_input'] !== '' ? $_GET['date_input'] : null;
$location_id = isset($_GET['location_id']) && $_GET['location_id'] !== '' ? $_GET['location_id'] : null;

// Check if the required parameters are not set or have empty values
if ($date === null || $location_id === null) {
    die("Invalid request. Please provide all required parameters with non-empty values.");
}

// Rest of your code goes here...


$Locations = GetLocations($link);
$CompanyInfo = GetCompanyInfo($link);
$Products = GetProducts($link);
$Units = GetUnit($link);

$LocationID = 1;
$dateTime = new DateTime($date);
$formattedDate = $dateTime->format('d/m/Y');

$generateDAte = new DateTime();
$reportDate = $generateDAte->format('d/m/Y H:i:s');
$LocationName = $Locations[$LocationID]['location_name'];

$pageTitle = "Day End Sale Report - " . $date;
$reportTitle = "Day End Sale Report";


$creditCardReceipts = $cashReceipts = $creditSales = $invoiceSales = $refundAmount = $cashInHand = 0;

$Locations = GetLocations($link);

$invoiceSales = getInvoicesByDate($link, $date, $location_id);
$receipts =  getReceiptsByDate($link, $date, $location_id);

if (isset($receipts[0])) {
    $cashReceipts = $receipts[0];
}

if (isset($receipts[1])) {
    $creditCardReceipts = $receipts[1];
}
$creditSales = $invoiceSales - $cashReceipts - $creditCardReceipts;
$cashInHand = $cashReceipts - $refundAmount;
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
                </table>
            </div>

        </div>



        <p style="font-weight:600;margin-top:10px; margin-bottom:0px">Report is generated on <?= $reportDate ?></p>
        <div id="container" class="section-4">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Location</th>
                        <th scope="col">Total Sale</th>
                        <th scope="col">Cash</th>
                        <th scope="col">Credit Card</th>
                        <th scope="col">Credit</th>
                        <th scope="col">Refund</th>
                        <th scope="col">Cash In Hand</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border-bottom"><?= $date ?></td>
                        <td class="border-bottom"><?= $location_name ?></td>
                        <td class="text-end border-bottom"><?= formatAccountBalance($invoiceSales) ?></td>
                        <td class="text-end border-bottom"><?= formatAccountBalance($cashReceipts) ?></td>
                        <td class="text-end border-bottom"><?= formatAccountBalance($creditCardReceipts) ?></td>
                        <td class="text-end border-bottom"><?= formatAccountBalance($creditSales) ?></td>
                        <td class="text-end border-bottom"><?= formatAccountBalance($refundAmount) ?></td>
                        <td class="text-end border-bottom"><?= formatAccountBalance($cashInHand) ?></td>

                    </tr>


                </tbody>
            </table>
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