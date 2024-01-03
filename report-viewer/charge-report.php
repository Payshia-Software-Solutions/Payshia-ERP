<?php
require_once('../include/config.php');
include '../include/function-update.php';
include '../include/finance-functions.php';
include '../include/reporting-functions.php';

$fromQueryDate = isset($_GET['from-date-input']) && $_GET['from-date-input'] !== '' ? $_GET['from-date-input'] : null;
$toQueryDate = isset($_GET['to-date-input']) && $_GET['to-date-input'] !== '' ? $_GET['to-date-input'] : null;
$location_id = isset($_GET['location_id']) && $_GET['location_id'] !== '' ? $_GET['location_id'] : null;

// Check if the required parameters are not set or have empty values
if ($fromQueryDate === null || $toQueryDate === null || $location_id === null) {
    die("Invalid request. Please provide all required parameters with non-empty values.");
}

// Rest of your code goes here...

$userAccounts = GetAccounts($link);

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

$pageTitle = "Charge Report - " . $fromQueryDate . " - " . $toQueryDate;
$reportTitle = "Charge Report";

$subTotal = $discountAmount = $serviceCharge = $grandTotal  = 0;

$invoiceSales = getChargeInvoicesByDateRangeAll($link, $fromQueryDate, $toQueryDate, $location_id);
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
        <div id="container" class="section-4">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Steward ID</th>
                        <th scope="col">Steward Name</th>
                        <th scope="col">Bill Count</th>
                        <th scope="col">Total Invoice</th>
                        <th scope="col">Charge Amount</th>
                        <th scope="col">Reserved(20%)</th>
                        <th scope="col">Receivable Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalInvoiceAmount = $totalChargeAmount = $totalReserveAmount = $totalReceivableAmount = $totalBillCount = 0;
                    if (!empty($invoiceSales)) {
                        foreach ($invoiceSales as $selectedArray) {

                            $stewardId = ($selectedArray['steward_id'] != '0') ? $selectedArray['steward_id'] : 'Direct';

                            if ($stewardId != "Direct") {
                                $LoggedStudent = $userAccounts[$selectedArray['steward_id']];
                                $stewardName =  $LoggedStudent['first_name'] . " " . $LoggedStudent['last_name'];
                            } else {
                                $stewardName = "Direct";
                            }

                            $chargeAmount = $selectedArray['chargeAmount'];
                            $reservedPotion = $chargeAmount * 0.2;
                            $receivableAmount = $chargeAmount - $reservedPotion;

                            $totalBillCount += $selectedArray['BillCount'];
                            $totalInvoiceAmount += $selectedArray['TotalInvoice'];
                            $totalChargeAmount += $chargeAmount;
                            $totalReserveAmount += $reservedPotion;
                            $totalReceivableAmount += $receivableAmount;
                    ?>
                            <tr>
                                <td><?= $stewardId ?></td>
                                <td><?= $stewardName ?></td>
                                <td class="text-end"><?= $selectedArray['BillCount'] ?></td>
                                <td class="text-end"><?= formatAccountBalance($selectedArray['TotalInvoice']) ?></td>
                                <td class="text-end"><?= formatAccountBalance($chargeAmount) ?></td>
                                <td class="text-end"><?= formatAccountBalance($reservedPotion) ?></td>
                                <td class="text-end"><?= formatAccountBalance($receivableAmount) ?></td>
                            </tr>

                    <?php
                        }
                    }
                    ?>
                    <tr>
                        <td scope="col" class="text-end border-bottom text-bold-extra" colspan="2">Total</td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= $totalBillCount ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($totalInvoiceAmount) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($totalChargeAmount) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($totalReserveAmount) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($totalReceivableAmount) ?></td>
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