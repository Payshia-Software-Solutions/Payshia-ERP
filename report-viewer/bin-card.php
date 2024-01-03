<?php
require_once('../include/config.php');
include '../include/function-update.php';
include '../include/finance-functions.php';
include '../include/reporting-functions.php';

$location_id = $_GET['location_id'];

$Locations = GetLocations($link);
$CompanyInfo = GetCompanyInfo($link);
$Products = GetProducts($link);
$Units = GetUnit($link);
$location_name = $Locations[$location_id]['location_name'];
$fromDate = isset($_GET['from-date-input']) && $_GET['from-date-input'] !== '' ? $_GET['from-date-input'] : null;
$toDate = isset($_GET['to-date-input']) && $_GET['to-date-input'] !== '' ? $_GET['to-date-input'] : null;
$location_id = isset($_GET['location_id']) && $_GET['location_id'] !== '' ? $_GET['location_id'] : null;
$select_product = isset($_GET['select_product']) && $_GET['select_product'] !== '' ? $_GET['select_product'] : null;

// Check if the required parameters are not set or have empty values
if ($fromDate === null || $toDate === null || $location_id === null || $select_product === null) {
    die("Invalid request. Please provide all required parameters with non-empty values.");
}

// Rest of your code goes here...


$binCard = stockBinCard($link, $fromDate, $toDate, $location_id, $select_product);
$product_name = $Products[$select_product]['product_name'];
$product_code = $Products[$select_product]['product_code'];

$forwardBalances = getCumulativeBinCardTotals($link, $fromDate, $select_product, $location_id);
$bfDebitBalance = $bfCreditBalance = 0;
if (!empty($forwardBalances)) {
    $bfDebitBalance = (isset($forwardBalances['DEBIT'])) ? $forwardBalances['DEBIT']['cumulative_total'] : 0;
    $bfCreditBalance = (isset($forwardBalances['CREDIT'])) ? $forwardBalances['CREDIT']['cumulative_total'] : 0;
}
// var_dump($forwardBalances);

$balanceForward = $bfDebitBalance - $bfCreditBalance;

$fromDate = new DateTime($fromDate);
$formattedFromQueryDate = $fromDate->format('d/m/Y');


$toDate = new DateTime($toDate);
$formattedToQueryDate = $toDate->format('d/m/Y');

// Report Detail
$generateDate = new DateTime();
$reportDate = $generateDate->format('d/m/Y H:i:s');

$pageTitle = "Bin Card - " . $product_name . " - " . $product_code;
$reportTitle = "Bin Card";
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
                        <th>Location</th>
                        <td class="text-end"><?= $location_name ?></td>
                    </tr>
                    <tr>
                        <th>From Date</th>
                        <td class="text-end"><?= $formattedFromQueryDate ?></td>
                    </tr>
                    <tr>
                        <th>To Date</th>
                        <td class="text-end"><?= $formattedToQueryDate ?></td>
                    </tr>
                    <tr>
                        <th>Code</th>
                        <td class="text-end"><?= $product_code ?></td>
                    </tr>
                    <tr>
                        <th>Product</th>
                        <td class="text-end"><?= $product_name ?></td>
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
                        <th scope="col">Description</th>
                        <th scope="col">Debit</th>
                        <th scope="col">Credit</th>
                        <th scope="col">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-bold-extra" colspan="2">B/F till <?= $formattedFromQueryDate ?></td>
                        <td class="text-bold-extra text-end"><?= number_format($bfDebitBalance, 3) ?></td>
                        <td class="text-bold-extra text-end"><?= number_format($bfCreditBalance, 3) ?></td>
                        <td class="text-bold-extra text-end"><?= number_format($balanceForward, 3) ?></td>
                    </tr>
                    <?php
                    if (!empty($binCard)) {
                        foreach ($binCard as $selectedArray) {
                            $debitQuantity = ($selectedArray['type'] == 'DEBIT') ? $selectedArray['quantity'] : 0;
                            $creditQuantity = ($selectedArray['type'] == 'CREDIT') ? $selectedArray['quantity'] : 0;

                            $bfDebitBalance += $debitQuantity;
                            $bfCreditBalance += $creditQuantity;

                            $balanceForward = $balanceForward + ($debitQuantity - $creditQuantity);

                            $date_time = date("Y-m-d", strtotime($selectedArray['created_at']));
                    ?>
                            <tr>
                                <td class=""><?= $date_time ?></td>
                                <td class="" style="max-width: 280px;"><?= $selectedArray['reference'] ?></td>
                                <td class="text-end"><?= number_format($debitQuantity, 3) ?></td>
                                <td class="text-end"><?= number_format($creditQuantity, 3) ?></td>
                                <td class="text-end"><?= number_format($balanceForward, 3) ?></td>
                            </tr>

                    <?php
                            $debitQuantity = $creditQuantity = 0;
                        }
                    }
                    ?>

                    <tr>
                        <td scope="col" class="text-end border-bottom text-bold-extra" colspan="2">Balance</td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= number_format($bfDebitBalance, 3) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= number_format($bfCreditBalance, 3) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= number_format($balanceForward, 3) ?></td>
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